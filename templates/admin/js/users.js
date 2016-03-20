	$(function(){
		
 $('#dg').datagrid({
                                
                                
                                
				view: detailview,
				detailFormatter:function(index,row){
				
                                
                                	return '<div class="ddv"></div>';
				},
                                
                                pageList: [20,50,100],                       

				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					
                                        ddv.panel({
                                            

                                        	border:false,
						cache:true,
                                           
                                                  
						href:"show_form.php?index="+index+"&uid="+row.user_id+"&gen="+row.gender,
                                              
						onLoad:function(){
                                                   
                                                   
                                              
							$('#dg').datagrid('fixDetailRowHeight',index);
							$('#dg').datagrid('selectRow',index);
							$('#dg').datagrid('getRowDetail',index).find('form').form('load',row);

                                                        
                                                   
						}

                                        });

					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});
		});
		function saveItem(index){
                  
                    
			var row = $('#dg').datagrid('getRows')[index];
			var url = row.isNewRecord ? 'save_user.php' : 'update_user.php?user_id='+row.user_id;
			$('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
				     
                                url: url,
				onSubmit: function(){
					return $(this).form('validate');
				
                                },
				success: function(data){
                                    
                                 
                                    
					data = eval('('+data+')');
                                        
                                   
					data.isNewRecord = false;
					$('#dg').datagrid('collapseRow',index);
					$('#dg').datagrid('updateRow',{
						index: index,
						row: data
                                                
					}
                                        
                                        
                                        );
                                        
                                           $('#dg').datagrid('reload');
				}
			});
		}
		function cancelItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			if (row.isNewRecord){
				$('#dg').datagrid('deleteRow',index);
			} else {
				$('#dg').datagrid('collapseRow',index);
			}
		}
		function destroyItem(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove this user?',function(r){
					if (r){
						var index = $('#dg').datagrid('getRowIndex',row);
						$.post('destroy_user.php',"&id="+row.user_id,function(){
                                                                
                                                                
							$('#dg').datagrid('deleteRow',index);
                                                        
                                                        $('#dg').datagrid('reload');
                                                        
						});
					}
				});
			}
		}
		function newItem(){
                     
			$('#dg').datagrid('appendRow',{isNewRecord:true});
			var index = $('#dg').datagrid('getRows').length - 1;
			$('#dg').datagrid('expandRow', index);
			$('#dg').datagrid('selectRow', index);
                        
                       
		}
                

    
    
        function doSearch(){  
        $('#dg').datagrid('load',{  
            email: $('#email').val(),
            user_id: $('#user_id').val()
           
        });  
    }  

   
         
                