/***************************************************************
	多级关联菜单 http://wanghui.name
***************************************************************/
	//菜单下拉框名称
	var arrSel = ['S1','S2','S3','S4','S5'];
	
//**********定义数组开始*****************************
	//菜单项格式：名称，值，[子项(没有子项为null)]
	// alert(menu);
	
//**********定义数组结束*****************************

//**********函  数***********************************
function multiLevelMenu(level)
{
	//通过数组下标(level)得到所对应的下拉框
	var obj;
	var subObj;
	var currMenu;
	var parentMenu;
	obj=document.getElementById(arrSel[level]);
    
	if(obj)
	{
		//通过obj.selectedIndex 找到其应该显示的项在menu数组中的下标引用位置
		var currMenu_varName = "menu";
		var parentMenu_value_varName = "menu";
		var parentMenu_text_varName = "menu";
		
		for(i=0;i<level;i++)
		{
			//如果selectedIndex 是n,就证明obj前面有3(n-1)(第一个null)个同一级的数组元素，
			//其子项在menu中的此一级中的下标位置就是 3(n-1)+2,即3n-1
			// level=0 currMenu_varName = "menu"
			// level=1 currMenu_varName = "menu[3n-1]"
			// level=2 currMenu_varName = "menu[3n-1][3n-1]" 依此类推...
			currMenu_varName += "[" + (document.getElementById(arrSel[i]).selectedIndex*3 -1) + "]";
			
			if (i==(level-1))
			{
				//存储当前菜单的父类的value属性的数组元素的名称
				parentMenu_value_varName += "[" + (document.getElementById(arrSel[i]).selectedIndex*3 -2) + "]";
				//存储当前菜单的父类的text属性的数组元素的名称
				parentMenu_text_varName  += "[" + (document.getElementById(arrSel[i]).selectedIndex*3-3) + "]"; 
			}
			else
			{		
				parentMenu_value_varName = currMenu_varName;
				parentMenu_text_varName  = currMenu_varName; 			
			}			
		}
		//alert(currMenu_varName);
		//把数组名字符串转换成对应的数组,此数组中存的就是当前菜单的数据
		
		currMenu = eval(currMenu_varName);

		if (currMenu == null)
		{
			//下级菜单如果是null,把下级菜单的值和text全部置为当前菜单的值和text
			parentMenu_value = eval(parentMenu_value_varName);
			parentMenu_text  = eval(parentMenu_text_varName);
			//alert(parentMenu_value);
			maxLevel = arrSel.length;
			for (i=level; i<maxLevel; i++)
			{
				subObj  = document.getElementById(arrSel[i]);
				//清空原有选项
				sel_len = subObj.length;
				for(j=0; j<sel_len; j++)
				{
					subObj.remove(0);
				}
				subObj.options.add(new Option('null', ''));
			}			
		}
		else
		{
			//清空原有选项
			//parentMenu_value = eval(parentMenu_value_varName);
			//parentMenu_text  = eval(parentMenu_text_varName);
			sel_len = obj.length;
			for(i=0;i<sel_len;i++)
			{
				obj.remove(0);
			}		
			//新的菜单项个数
			new_sel_len = Math.floor(currMenu.length/3);
			//把currMenu中的值和文本加入菜单(obj)		
			obj.options.add(new Option('null',''));	
			for(i=0;i<new_sel_len;i++)
			{	
				obj.options.add(new Option(currMenu[i*3],currMenu[i*3+1]));
			}
			//obj.options.add(new Option('null', ''));
			//指定下一级菜单onchange事件
			//obj.onchange = alert("onchange");
			obj.onchange = Function("multiLevelMenu(" + (level+1) + ")");		
			//递归调用
			multiLevelMenu(level+1);			
		}		
	}
	else
	{
		//退出递归
		return false;
	}
}
multiLevelMenu(0);

////////////////////////////////////////////////////////////////////////////////////////////

//显示当前的分类

selected_menu(document.getElementById('S1'),0,part_1);
selected_menu(document.getElementById('S2'),1,part_2);
selected_menu(document.getElementById('S3'),2,part_3);
selected_menu(document.getElementById('S4'),3,part_4);
selected_menu(document.getElementById('S5'),4,part_5);

function selected_menu(obj,level,sel_value)
{
	len=obj.length;
	for(i=0;i<len;i++)
	{
		if(obj.options[i].value==sel_value)
		{
			obj.options[i].selected=true;
			multiLevelMenu(level+1);
			break;
		}
	}
}