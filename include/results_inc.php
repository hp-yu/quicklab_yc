<?php
Class Pager
{
   var $PageSize;             //ÿҳ����
   var $CurrentPageID;        //��ǰ��ҳ��
   var $NextPageID;           //��һҳ
   var $PreviousPageID;       //��һҳ
   var $numPages;             //��ҳ��
   var $numItems;             //�ܼ�¼��
   var $isFirstPage;          //�Ƿ��һ�?
   var $isLastPage;           //�Ƿ����һ�?
   var $sql;                  //sql��ѯ���?

  function Pager($option)
   {
       global $db_conn;
       $db_conn=db_connect();
       $this->_setOptions($option);
       // ������
       if ( !isset($this->numItems) )
       {
           $res = $db_conn->query($this->sql);
           $this->numItems = $res->num_rows;
       }
       // ��ҳ��
       if ( $this->numItems > 0 )
       {
           if ( $this->numItems < $this->PageSize ){ $this->numPages = 1; }
           if ( $this->numItems % $this->PageSize )
           {
               $this->numPages= (int)($this->numItems / $this->PageSize) + 1;
           }
           else
           {
               $this->numPages = $this->numItems / $this->PageSize;
           }
       }
       else
       {
           $this->numPages = 0;
       }

       switch ( $this->CurrentPageID )
       {
           case $this->numPages == 1:
               $this->isFirstPage = true;
               $this->isLastPage = true;
               break;
           case 1:
               $this->isFirstPage = true;
               $this->isLastPage = false;
               break;
           case $this->numPages:
               $this->isFirstPage = false;
               $this->isLastPage = true;
               break;
           default:
               $this->isFirstPage = false;
               $this->isLastPage = false;
       }

       if ( $this->numPages > 1 )
       {
           if ( !$this->isLastPage ) { $this->NextPageID = $this->CurrentPageID + 1; }
           if ( !$this->isFirstPage ) { $this->PreviousPageID = $this->CurrentPageID - 1; }
       }

       return true;
   }

   /***
    *
    * ���ؽ�����ݿ�?l��
    * �ڽ��Ƚϴ��ʱ�����ֱ��ʹ�����������ݿ�l�ӣ�Ȼ������֮������������С
    * ������Ǻܴ�?����ֱ��ʹ��getPageData�ķ�ʽ��ȡ��ά�����ʽ�Ľ��
    * getPageData����Ҳ�ǵ��ñ�����4��ȡ����
    *
    ***/

   function getDataLink()
   {
       if ( $this->numItems )
       {
           global $db_conn;

           $PageID = $this->CurrentPageID;

           $from = ($PageID - 1)*$this->PageSize;
           $count = $this->PageSize;
           $query="$this->sql limit $from, $count";
           $link = $db_conn->query($query);   //ʹ��Pear DB::limitQuery������֤��ݿ������

           return $link;
       }
       else
       {
           return false;
       }
   }

   /***
    *
    * �Զ�ά����ĸ�ʽ���ؽ��
    *
    ***/
   function getPageData()
   {
       if ( $this->numItems )
       {
           if ( $res = $this->getDataLink() )
           {
               if ( $res->num_rows)
               {
                   $result = $res->fetch_array() ;
               }
               else
               {
                   $result = array();
               }

               return $result;
           }
           else
           {
               return false;
           }
       }
       else
       {
           return false;
       }
   }

   function _setOptions($option)
   {
       $allow_options = array(
                   'PageSize',
                   'CurrentPageID',
                   'sql',
                   'numItems'
       );

       foreach ( $option as $key => $value )
       {
           if ( in_array($key, $allow_options) && ($value != null) )
           {
               $this->$key = $value;
           }
       }

       return true;
   }
}
function turnover($isFirstPage,$isLastPage,$numItems,$numPages,$PreviousPageID,$NextPageID,$getURL)
{
	//$old_url = $_SERVER["REQUEST_URI"];
	if ( $isFirstPage ) {
		$turnover = "First|Previous|";
	}
	else {
		if (strpos($getURL,'?')===false) {
			$turnover = "<a href='".$getURL."?page=1'>First</a>|<a href='".
			$getURL."?page=".$PreviousPageID."'>Previous</a>|";
		}
		else {
			$turnover = "<a href='".$getURL."&page=1'>First</a>|<a href='".
			$getURL."&page=".$PreviousPageID."'>Previous</a>|";
		}
	}
	if ( $isLastPage ) {
		$turnover .= "Next|Last";
	}
	else {
		if (strpos($getURL,'?')===false) {
			$turnover .= "<a href='".$getURL."?page=".$NextPageID."'>Next</a>|<a href='".
			$getURL."?page=".$numPages."' >Last</a>";
		}
		else {
			$turnover .= "<a href='".$getURL."&page=".$NextPageID."'>Next</a>|<a href='".
			$getURL."&page=".$numPages."' >Last</a>";
		}
	}
	return $turnover;
}

function getURL($fields)
{
	foreach ($fields as $field)
	{
		if(isset($_REQUEST[$field])&&$_REQUEST[$field]!=''&&$_REQUEST[$field]!='%')
		{
		$getURL.="$field=".$_REQUEST[$field]."&";
		}
	}
  return $getURL;
}
function selectedRequest($module_name) {
  if(isset($_REQUEST['actionType'])&&$_REQUEST['actionType']!=''){
    if($_REQUEST['actionType']=='clipboard') {
      clipboard($module_name);
    }
    if($_REQUEST['actionType']=='store') {
  	  $module=get_id_from_name('modules',$module_name);
      $_SESSION['selecteditemStore']=$_REQUEST['selectedItem'];
      header('location:storages_operate.php?type=add&module_id='.$module['id']);
    }
    if($_REQUEST['actionType']=='delete') {
      $_SESSION['selecteditemDel']=$_REQUEST['selectedItem'];
      header('location:'.$module_name.'_operate.php?type=delete');
    }
  }
}
function clipboard($module_name)
{
  	$num_clipboard=count($_SESSION['clipboard']);

	$selecteditem=$_REQUEST['selectedItem'];
	$num_selecteditem=count($selecteditem);

	$module=get_record_from_name('modules',$module_name);
	$module_id=$module['id'];

	for($i=0;$i<$num_selecteditem;$i++)
	{
	  if(isset($_SESSION['clipboard'][$module_id."_".$selecteditem[$i]]))
	  {
	    $_SESSION['clipboard'][$module_id."_".$selecteditem[$i]]++;
	  }
	  else {
	  	$_SESSION['clipboard'][$module_id."_".$selecteditem[$i]]=1;
	  }
	}
	//header("location:#");
}

function getmicrotime(){
list($usec, $sec) = explode(" ",microtime());
return ((float)$usec + (float)$sec);
}
?>
