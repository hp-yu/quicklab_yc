/***************************************************************
	�༶�����˵� http://wanghui.name
***************************************************************/
	//�˵�����������
	var arrSel = ['S1','S2','S3','S4','S5'];
	
//**********�������鿪ʼ*****************************
	//�˵����ʽ�����ƣ�ֵ��[����(û������Ϊnull)]
	// alert(menu);
	
//**********�����������*****************************

//**********��  ��***********************************
function multiLevelMenu(level)
{
	//ͨ�������±�(level)�õ�����Ӧ��������
	var obj;
	var subObj;
	var currMenu;
	var parentMenu;
	obj=document.getElementById(arrSel[level]);
    
	if(obj)
	{
		//ͨ��obj.selectedIndex �ҵ���Ӧ����ʾ������menu�����е��±�����λ��
		var currMenu_varName = "menu";
		var parentMenu_value_varName = "menu";
		var parentMenu_text_varName = "menu";
		
		for(i=0;i<level;i++)
		{
			//���selectedIndex ��n,��֤��objǰ����3(n-1)(��һ��null)��ͬһ��������Ԫ�أ�
			//��������menu�еĴ�һ���е��±�λ�þ��� 3(n-1)+2,��3n-1
			// level=0 currMenu_varName = "menu"
			// level=1 currMenu_varName = "menu[3n-1]"
			// level=2 currMenu_varName = "menu[3n-1][3n-1]" ��������...
			currMenu_varName += "[" + (document.getElementById(arrSel[i]).selectedIndex*3 -1) + "]";
			
			if (i==(level-1))
			{
				//�洢��ǰ�˵��ĸ����value���Ե�����Ԫ�ص�����
				parentMenu_value_varName += "[" + (document.getElementById(arrSel[i]).selectedIndex*3 -2) + "]";
				//�洢��ǰ�˵��ĸ����text���Ե�����Ԫ�ص�����
				parentMenu_text_varName  += "[" + (document.getElementById(arrSel[i]).selectedIndex*3-3) + "]"; 
			}
			else
			{		
				parentMenu_value_varName = currMenu_varName;
				parentMenu_text_varName  = currMenu_varName; 			
			}			
		}
		//alert(currMenu_varName);
		//���������ַ���ת���ɶ�Ӧ������,�������д�ľ��ǵ�ǰ�˵�������
		
		currMenu = eval(currMenu_varName);

		if (currMenu == null)
		{
			//�¼��˵������null,���¼��˵���ֵ��textȫ����Ϊ��ǰ�˵���ֵ��text
			parentMenu_value = eval(parentMenu_value_varName);
			parentMenu_text  = eval(parentMenu_text_varName);
			//alert(parentMenu_value);
			maxLevel = arrSel.length;
			for (i=level; i<maxLevel; i++)
			{
				subObj  = document.getElementById(arrSel[i]);
				//���ԭ��ѡ��
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
			//���ԭ��ѡ��
			//parentMenu_value = eval(parentMenu_value_varName);
			//parentMenu_text  = eval(parentMenu_text_varName);
			sel_len = obj.length;
			for(i=0;i<sel_len;i++)
			{
				obj.remove(0);
			}		
			//�µĲ˵������
			new_sel_len = Math.floor(currMenu.length/3);
			//��currMenu�е�ֵ���ı�����˵�(obj)		
			obj.options.add(new Option('null',''));	
			for(i=0;i<new_sel_len;i++)
			{	
				obj.options.add(new Option(currMenu[i*3],currMenu[i*3+1]));
			}
			//obj.options.add(new Option('null', ''));
			//ָ����һ���˵�onchange�¼�
			//obj.onchange = alert("onchange");
			obj.onchange = Function("multiLevelMenu(" + (level+1) + ")");		
			//�ݹ����
			multiLevelMenu(level+1);			
		}		
	}
	else
	{
		//�˳��ݹ�
		return false;
	}
}
multiLevelMenu(0);

////////////////////////////////////////////////////////////////////////////////////////////

//��ʾ��ǰ�ķ���

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