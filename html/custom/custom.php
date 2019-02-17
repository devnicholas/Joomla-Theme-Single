<?php
require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();
$user = JFactory::getUser();
$session = JFactory::getSession();
$db = JFactory::getDBO();

class CustomFields {
	
	public function getAnchorHeader($id = null)
	{
	
		if(!$id){
			return;
		}
		
		return $this->getFields($id);
	
	}
	
	public function getFieldsByAnchor($id = null, $prefix = null)
	{
	
		if(!$id){
			return;
		}
		
		if(!$prefix){
			$prefix = 'categoria';
		}
		
		$db = JFactory::getDBO();
		
		$sql_cat = " SELECT alias FROM #__categories WHERE id='".$id."' ";
		$db->setQuery($sql_cat);
		$res_cat = $db->loadAssoc();
		if($res_cat["alias"]!=""){
			$sql_anchor = " SELECT id FROM #__content WHERE alias='".$prefix."-".$res_cat["alias"]."' ";
			$db->setQuery($sql_anchor);
			$res_anchor = $db->loadAssoc();
			if($res_anchor["id"]){
				return $this->getFields($res_anchor["id"]);
			} else {
				return null;
			}
		} else {
			return null;
		}
	
	}
	
	public function getFieldsValuesByCode($code = null, $id = null)
	{
		
		if(!$code){
			return;
		}
		
		$db = JFactory::getDBO();
		
		$query = " SELECT id,name FROM #__fields WHERE name='".$code."' ";
		$db->setQuery($query);
		$result = $db->loadAssoc();
		
		if($result["id"]){
			
			$subquery = " SELECT value FROM #__fields_values WHERE item_id='".$id."' AND field_id='".$result["id"]."' ";
			$db->setQuery($subquery);
			$resultsub = $db->loadAssocList();
			
			$arr = array();
			foreach($resultsub as $i => $v){
				$arr[] = $v["value"];
			}
			
			return $arr;
			
		} else {
			return false;
		}
		
	}
	
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
	
	public function getChildren($id) {
		
		if(!$id){
			return;
		}
		
		$db = JFactory::getDBO();
		
          $order="";
          if($id=="19"){
            $order = "ORDER BY description";
          }
		$query = " SELECT * FROM #__categories WHERE parent_id='".$id."' AND published='1' ".$order." ";
		$db->setQuery($query);
		$result = $db->loadAssocList();
		
		if($result[0]){
			echo '<ul>';
				foreach($result as $i => $v){
					$params = json_decode($v["params"]);
					$image = 0;
					if($params->image!="") {
						$image = '<span class="image"><img border="0" src="'.JURI::base().$params->image.'" /></span>';
					} else {
						$image = '';
					}
					$subquery = " SELECT id FROM #__categories WHERE parent_id='".$v["id"]."' AND published='1' ".$order." ";
					$db->setQuery($subquery);
					$resultsub = $db->loadAssocList();
					if(count($resultsub)>0){
						echo '<li class="parent">';
							echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($v["id"])).'">'.$image.'<span class="title">'.$v["title"].' <i class="fa fa-arrow-circle-down"></i></span></a>';
					} else {
						echo '<li>';
							echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($v["id"])).'">'.$image.'<span class="title">'.$v["title"].'</span></a>';
					}
						$this->getChildren($v["id"]);
					echo '</li>';
				}
        
			echo '</ul>';		
		}
		
	}
	
	public function getFilterValues($field) {
		
		if(!$field){
			return;
		}
		
		$db = JFactory::getDBO();
		
		$sql = " SELECT id,title,name FROM #__fields WHERE name='".$field."' ";
		$db->setQuery($sql);
		$res = $db->loadAssoc();
		echo '<select id="'.$field.'" name="'.$field.'" data-tipo="'.$field.'" onchange="tagadd(this)">';
		if($res["id"]){
			$sqlvalue = " SELECT value FROM #__fields_values WHERE field_id='".$res["id"]."' GROUP BY value ORDER BY value ASC ";
			$db->setQuery($sqlvalue);
			$resvalue = $db->loadAssocList();
			if($resvalue[0]){
				echo '<option value>'.$res["title"].'</option>';
				foreach($resvalue as $i => $v){
					echo '<option value="'.$v["value"].'">'.$v["value"].'</option>';
        }
			} else {
				echo '<option value="">Nenhum resultado encontrado</option>';
			}
		}
		echo '</select>';
		
		
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

?>