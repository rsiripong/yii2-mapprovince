/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


		
		var iconData = {
  "us": { width: 24, height: 14 },
  "ca": { width: 24, height: 14 },
  "flag-shadow": { width: 40, height: 30 },
 "house": { width: 32, height: 32 },
  "house-shadow": { width: 59, height: 32 },
  "headquarters": { width: 32, height: 32 },
  "headquarters-shadow": { width: 59, height: 32 },
   "people35": { width: 32, height: 32 },
   "Industry-Factory-icon": { width: 32, height: 32 },
};

  
            //<![CDATA[
            var map;
            var mgr;
            var icons = {};
            var allmarkers = [];
			//var infowindow = [];
			var infowindowtmp ;
                        
                        

			function rendermarkers(map){
			
				var ne = map.getBounds().getNorthEast();
				var sw = map.getBounds().getSouthWest();
				var zm = map.getZoom();
				
				//console.log("zoom change "+ne[0]+"|"+sw);
                //  updateStatus(mgr.getMarkerCount(map.getZoom()));
				//alert(ne[0]+'|'+ne[1])
				
				$.get( ajaxurldata,{ 
						ne: [ne.lat(),ne.lng()], 
						sw: [sw.lat(),sw.lng()],
						zm:zm	
						}, function(data){
				mgr.clearMarkers();
				var markers2 = [];
				$(data).find("marker").each(function(){
 
					var title = $(this).attr("name");
					var markerid = $(this).attr("id");
					var markertype = $(this).attr("type");
					
					var point = new google.maps.LatLng(
								parseFloat($(this).attr('lat')),
								parseFloat($(this).attr('lng')));
 
 
					//console.log(title+"|"+point);

			  var icon = [];
                          
                          if(markertype != "buyer"){
			  if(zm < 9){
			  //mgr.addMarkers(markers2, 0, 8);
			  icon = ["headquarters", "headquarters-shadow"];
			  }else if(zm < 12){
			  //mgr.addMarkers(markers2, 9, 11);
			  icon = ["house", "house-shadow"];
			  }else if(zm < 20){
			  //mgr.addMarkers(markers2, 12, 19);
			  icon = ["people35", "house-shadow"];
			  }
                          }else{
                              icon = ["Industry-Factory-icon", "house-shadow"];
                          }
			  var marker = createMarker(point, title, getIcon(icon), markerid ,markertype);
			  
			 
			  markers2.push(marker);
			  allmarkers.push(marker);
			  });
			  if(zm < 9){
			  mgr.addMarkers(markers2, 0, 8);
			  }else if(zm < 12){
			  mgr.addMarkers(markers2, 9, 11);
			  }else if(zm < 20){
			  mgr.addMarkers(markers2, 12, 19);
			  }
   
    mgr.show();
   });
				  
				  
			
			}
            
			
			
            function getIcon(images) {
              var icon = false;
              if (images) {
                if (icons[images[0]]) {
                  icon = icons[images[0]];
                } else {                    
                    var iconImage = new google.maps.MarkerImage(assetspath+'images/' + images[0] + '.png',
                      new google.maps.Size(iconData[images[0]].width, iconData[images[0]].height),
                      new google.maps.Point(0,0),
                      new google.maps.Point(0, 32));
                    
                    var iconShadow = new google.maps.MarkerImage(assetspath+'images/' + images[1] + '.png',
                      new google.maps.Size(iconData[images[1]].width, iconData[images[1]].height),
                      new google.maps.Point(0,0),
                      new google.maps.Point(0, 32));
                    
                    var iconShape = {
                      coord: [1, 1, 1, 32, 32, 32, 32, 1],
                      type: 'poly'
                    };

                    icons[images[0]] = {
                      icon : iconImage,
                      shadow: iconShadow,
                      shape : iconShape
                    };
                }
              }
              return icon;
            }
			
            function createMarker(posn, title, icon,id,type) {
              var markerOptions = {
                position: posn,
                title: title,
				//label:"\n\n5",
              };
			 
              if(icon !== false){
                markerOptions.shadow = icon.shadow;
                markerOptions.icon   = icon.icon;
                markerOptions.shape  = icon.shape;
              }
			  
			  
                var marker = new google.maps.Marker(markerOptions);
				 

			  
			 

              google.maps.event.addListener(marker, 'dblclick', function() {
			  var zm = map.getZoom();
			 
				map.setCenter(marker.getPosition());
				map.setZoom(zm+3);
                
             });
			  
			  google.maps.event.addListener(marker, 'click', function() {
                
				if(infowindowtmp){
				infowindowtmp.close();
				}
				
				this2 = this;
				$.ajax({   
					url:ajaxurlinfo,//ใช้ ajax ใน jQuery ดึงข้อมูล   
					data:'id='+id+'&type='+type,// ส่งค่าตัวแปร ไปดึงข้อมูลจากฐานข้อมูล
					async:false  ,
					success: function(msg){
						
						var iw = new google.maps.InfoWindow({content: msg	});
						iw.open(map, this2);
						infowindowtmp=iw;
					}

				}) 
				 
              });
			  
			  
              return marker;
            }

            function showMarkers() {
              mgr.show();
              updateStatus(mgr.getMarkerCount(map.getZoom()));
            }
            
            function hideMarkers() {
              mgr.hide();
              updateStatus(mgr.getMarkerCount(map.getZoom()));
            }
            
            function deleteMarker() {
              var markerNum = parseInt(document.getElementById("markerNum").value);
              mgr.removeMarker(allmarkers[markerNum]);
              updateStatus(mgr.getMarkerCount(map.getZoom()));
            }
            
            function clearMarkers() {
              mgr.clearMarkers();
              updateStatus(mgr.getMarkerCount(map.getZoom()));
            }
            
            function reloadMarkers() {
              setupOfficeMarkers();
            }
            
            function updateStatus(html) {
              document.getElementById("status").innerHTML = html;
            }
            
			
			                        