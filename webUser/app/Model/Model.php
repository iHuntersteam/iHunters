<?php

namespace App\Model;

use \App\Connector;
abstract class Model
{
    protected $model;//будем хранить имя модели
    protected $connection;//будем хранить соединение с бд
    protected $table;//название таблиц
    protected $fields;//поля
    protected $primaryKey = 'id';
    protected $fillable = [];//масссив какие поля разрешены для заполнения
    protected $guarded = [];//какие поля запрещены для заполнения
    protected $specialchars = [];
    protected $dateFormat;
    protected $datetimeFormat;
    public $collection = [];

    public function __construct()
    {
        $this->class = get_class($this); // сохраняем имя модели, т.к. класс наследуется, то construct выполяентся в другом классе и мы берем имя другого объекта
        $reflectionClass = new \ReflectionClass($this->class);
               $this->table = strtolower($reflectionClass->getShortName());//имя таблицы с которой будем работать
          $this->connection = Connector::createConnector();//либо создается новый объект либо берется существующей
          $this->connection->connect();
          
          $this->fields = $this->connection->fieldNameArrayByTable($this->table, false, false);
          $this->datetimeFormat = $this->connection->datetime;
          $this->dateFormat = $this->connection->date;
        }

        public function getAll()
        {

          $this->collection = $this->connection->select('*', $this->table);        
        }

        public function getAllStats($value)
        {
          $this->collection = $this->connection->selectAllStats($value);        
        }

        public function getDailyStats()
        {

         $siteId = $_POST['siteId'];
         $beginDate = $_POST['beginDate'];
         $endDate = $_POST['endDate'];
         $personId = $_POST['personId'];

         $this->collection = $this->connection->selectDailyStats($siteId, $beginDate, $endDate, $personId); 
       }

        public function getLastScanDate($value)
        {
          $this->collection = $this->connection->selectLastScanDate($value);        
        }


       public function getByPk($value)
       {
         $this->collection = $this->connection->selectOne('*', $this->table, $this->primaryKey." = $value");

       }

       public function add($item) {

        foreach ($item as $key => &$value) {
          if (in_array($key, $this->guarded) || !in_array($key, $this->fillable)) unset($item[$key]);
          if (in_array($key, $this->specialchars)) $value = htmlspecialchars($value);
        }

        if ($this->connection->insert($item, $this->table)) {

          $item[$this->primaryKey] =  $this->connection->insertId();
          $this->collection[] = $item;
        }
      }

      public function del($item) {
        $item=htmlspecialchars(implode(",",$item));
        $this->connection->delete($this->table,$item); 
      }

      public function updates($item) {

        $id=intval($item['id']);

        foreach ($item as $key => &$value) {
          if (in_array($key, $this->guarded) || !in_array($key, $this->fillable)) unset($item[$key]);
          if (in_array($key, $this->specialchars)) $value = htmlspecialchars($value);
        }

        if ($this->connection->update($item, $this->table, $id)) {
          $this->collection[] = $item;
        }
      }

    }

