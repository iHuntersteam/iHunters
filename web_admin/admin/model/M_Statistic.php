<?php 


class M_Statistic 
{
	
	
	public static function siteAllPersonRank($id)
	{
		$db=M_Mysql::getInstance();
		$query="SELECT * FROM person_page_rank WHERE page_id='$id'";
		$rows=$db->select($query);
		return $rows;
	}
}

?>