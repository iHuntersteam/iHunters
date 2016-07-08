<?php 
class M_Statistic 
{
	
	
	public static function siteAllPersonRank($id)
	{
		$db=M_Mysql::getInstance();
		$id=$db->mres($id);
		$query="SELECT * FROM person_page_rank WHERE page_id='%d'";
		$result=sprintf($query,$id);
		$rows=$db->select($result);
		return $rows;
	}
	public static function rankByDay($site_id,$date,$person_id)
	{
		$db=M_Mysql::getInstance();
		$query="SELECT page_id FROM person_page_rank JOIN pages ON pages.id=person_page_rank.page_id WHERE site_id='%d' AND date_modified ='$date'AND person_id='%d'";
		$site_id=$db->mres($site_id);
		$person_id=$db->mres($person_id);
		$result=sprintf($query,$site_id,$person_id);
		$rows=$db->select($result);
		return $rows;
	}
}
?>
