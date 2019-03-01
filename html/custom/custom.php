<?php
class Fields {
	
	public function getFields($id = null)
	{
		
		if(!$id){
			return;
		}
		
		$db = JFactory::getDBO();
		
		$query = " SELECT value,field_id FROM #__fields_values WHERE item_id='".$id."' ";
		$db->setQuery($query);
		$result = $db->loadAssocList();

		if(!empty($result)){
			
			$return = array();
			
			foreach($result as $field){
				
				$subquery = " SELECT name FROM #__fields WHERE id='".$field["field_id"]."' ";
				$db->setQuery($subquery);
				$resultsub = $db->loadAssoc();
				
				$return[self::replaceString($resultsub["name"])] = $field["value"];
				
			}
			
			return json_encode($return);
			
		} else {
			return false;
		}
		
	}

	public function getCategorySons($cat) {
		
		if(!$cat){
			return;
		}
		
		$db = JFactory::getDBO();
		$return = "";
		$sql = " SELECT id FROM #__categories WHERE parent_id='".$cat."' ";
		$db->setQuery($sql);
		$res = $db->loadAssocList();
		foreach($res as $i => $n){
			$return.=$n['id'];
			if($n['id']!=""){
				$bool = true;
			}
			$tmp=$n['id'];
			while($bool){
				$sql = " SELECT id FROM #__categories WHERE parent_id='".$tmp."' ";
				$db->setQuery($sql);
				$result = $db->loadAssoc();
				if($result['id']!=""){
					$tmp=$result['id'];
					$return.=",".$tmp;
				}else{
					$bool=false;
				}      
			}
			if($res[$i+1]['id']!=""){
				$return.=",";
			}  
		}	
		return $return;	
	}

	public function getCategoryDads($cat, $limiter) {
		
		if(!$cat){
			return;
		}
		if(!$limiter){
			$limiter=1;
		}
		
		$db = JFactory::getDBO();
		$return = "";
		$sql = " SELECT id FROM #__categories WHERE id='".$cat."' ";
		$db->setQuery($sql);
		$res = $db->loadAssocList();
		foreach($res as $i => $n){
			$return.=$n['id'];
			if($n['id']!=""){
				$bool = true;
			}
			$tmp=$n['id'];
			while($bool){
				$sql = " SELECT parent_id FROM #__categories WHERE id='".$tmp."' ";
				$db->setQuery($sql);
				$result = $db->loadAssoc();
				if($result['parent_id']!="" and $result['parent_id']>$limiter){
					$tmp=$result['parent_id'];
					$return.=",".$tmp;
				}else{
					$bool=false;
				}      
			}  
		}	
		return $return;	
	}

	public function getCategory($cat) {
		
		if(!$cat){
			return;
		}
		
		$db = JFactory::getDBO();
		$return = "";
		$sql = " SELECT title FROM #__categories WHERE id='".$cat."' ";
		$db->setQuery($sql);
		$res = $db->loadAssoc();
		$return = $res['title'];	
		return $return;	
	}
	
	public function replaceString($string){
		
		$string = str_replace("-", "_", $string);
		
		return $string;
		
	}

	public function convertPrice($num){
		if(!$num){
			return;
		}
		if($num==0){
			return 'R$0,00';
		}
		return 'R$' . number_format($num, 2, ',', '.');
	}
	
}

/**
 * Classe de operações no banco
 */
class DB {
	public function q($value){
		$db = JFactory::getDBO();
		return $db->q($value); 
	}

	public function select($values=null,$table,$where=null,$order=null,$limit=null,$group=null){
		if($table==null){
			return false;
		}
		if ($values == null) {
			$values = "*";
		}
		$db = JFactory::getDBO();
		$sql = "SELECT ".$values." FROM ".$table."";
		if($where!=null){
			$sql.=" WHERE ".$where;
		}
		if($order){
			$sql.=" ORDER BY ".$order;
		}
		if($limit){
			$sql.=" LIMIT ".$limit;
		}
		if($group){
			$sql.=" GROUP BY ".$group;
		}
		$db->setQuery($sql);
		return $db->loadAssocList();
	}

	public function insert($table,$values){
		if($table==null or $values==null){
			return;
		}
		$db = JFactory::getDBO();
		$columns = array();
		$_values = array();
		foreach ($values as $key => $value) {
			if (!in_array($key, $columns)) {
				$columns[] = $key;
			}
			$_values[] = $value;
		}
		$_columns = implode(",", $columns);
		$__values = implode(",", $_values);
		$sql = " INSERT INTO ".$table." (".$_columns.") VALUES (".$__values.") ";
		$db->setQuery($sql);
		$db->query();
	}

	public function delete($table,$where){
		if($table==null or $where==null){
			return;
		}
		$db = JFactory::getDBO();
		$sqlDelete = "DELETE FROM ".$table."";
		if($where!=null){
			$sqlDelete.=" WHERE ".$where;
		}
		$db->setQuery($sqlDelete);
		$db->query();
	}

	public function update($table,$values,$where){
		if($table==null or $values==null or $where==null){
			return false;
		}
		$db = JFactory::getDBO();
		$sql = "UPDATE ".$table." SET ";
		$count = count($values);
		$i=0;
		foreach($values as $name => $value){
			$i++;
			$sql.= $name."=".$db->q($value);
			if($i<$count){
				$sql.=", ";
			}
		}
		$sql.=" WHERE ".$where;
		$db->setQuery($sql);
		$db->query();
	}
}

?>
