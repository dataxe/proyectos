//--------------------------------------------------------------------------------
// Objeto calendario
//--------------------------------------------------------------------------------
function Calendario( padre, flechaIzq, flechaDer ){																				
					
	//--------------------------------------------------------------------------------										
	
	this.obj=document.createElement('table');
	this.obj.cellPadding=0;
	this.obj.cellSpacing=0;
	this.obj.border=1;
	this.obj.className='calendario';
	
	var caption, thead, tr, th, tbody, td, a, fecha, boton;
	
	caption=document.createElement('caption');
	
	if( flechaIzq ){
		boton=document.createElement('a');
		boton.id='flechaIzq';
		boton.href='javascript:selector.izquierda()';
		boton.className='botonIzq';
		caption.appendChild( boton );
	}
	if( flechaDer ){
		boton=document.createElement('a');
		boton.id='flechaDer';
		boton.href="javascript:selector.derecha()";
		boton.className='botonDer';
		caption.appendChild( boton );
	}
	
	caption.appendChild( document.createTextNode( 'caption' ) );
	this.obj.appendChild(caption);				
	
	thead=document.createElement('thead');
	this.obj.appendChild(thead);
	
	tr=document.createElement('tr');
	thead.appendChild(tr);				
	
	for(var dia=0; dia<7; dia++){		
		th=document.createElement('th');
		th.appendChild( document.createTextNode( dias[ dia ] ) );			
		if(dia==6){
			th.className='domingo';
		}
		tr.appendChild(th);
	}				
		
	var tbody=document.createElement('tbody');
	this.obj.appendChild(tbody);									

	for(var sem=0; sem<6; sem++){
		tr=document.createElement('tr');
		for( var dia=0; dia<7; dia++ ){
			td=document.createElement('td');				
			if(dia==6){
				td.className='domingo';
			}								
			tr.appendChild(td);
			
			a=document.createElement('a');
			a.appendChild( document.createTextNode( ' ' ) );
			a.id="";
			a.href="";
			td.appendChild( a );								
		}
		tbody.appendChild(tr);
	}				
	
	padre.appendChild( this.obj );				
	
	//--------------------------------------------------------------------------------
	
	this.borrar=function(){
		var td, a;
		
		for( var sem=0; sem<6; sem++ ){
			for( var dia=0; dia<7; dia++ ){						
				td=tbody.childNodes[sem].childNodes[dia];
				a=td.childNodes[0];					
				a.id="";
				a.href="";
				a.className="";
				a.childNodes[0].nodeValue=" ";					
			}
		}
	}
	
	//--------------------------------------------------------------------------------
	
	this.setFecha=function( mes, any ){
		var numDia=1;
		var fecha=new Date();
		var caption=this.obj.childNodes[0];
		var tbody=this.obj.childNodes[2];
		var td, a, fecha;
        var html, estilo;
						
		this.borrar();
		
		if( caption.childNodes.length==1 ){				
			caption.childNodes[0].nodeValue=meses[mes]+" "+any;
		}else if( caption.childNodes.length==2 ){				
			caption.childNodes[1].nodeValue=meses[mes]+" "+any;
		}
		
		fecha=new Date( any, mes, 1 );
		var diaInicio=fecha.getDay()-1;
		if(diaInicio==-1){
			diaInicio=6;
		}        
	
		while(fecha.getMonth()==mes){			
			for( var sem=0; sem<6; sem++ ){
				for( var dia=diaInicio; dia<7 && fecha.getMonth()==mes; dia++ ){						
					td=tbody.childNodes[sem].childNodes[dia];						
					var id=fecha.getDate()+"/"+mes+"/"+any;
					
					html='';
                    estilo='';
                    if( compararFechas( fecha, fechaActual ) < 0 ){
                        estilo = 'style="text-decoration: line-through" ';
                    }
                    
					html+='<a id="'+id+'" class="dia" '+estilo+'href="javascript:void(0)" onmouseover="diaOver(this)" onmouseout="diaOut(this)" onclick="selector.setDia(\''+id+'\')">';
					html+=fecha.getDate();                    
					html+='</a>';
					td.innerHTML=html;												
					
					fecha.setDate( ++numDia );
					diaInicio=0;
				}
			}
			fecha.setDate( numDia++ );
		}
	}
}