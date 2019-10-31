$(document).ready(function(){
	$.ajax({
		url: "pedData.php",
		method: "GET",
		success: function(data) {
      console.log(data);
      var pedCount = [];
      var cycCount = [];
      var hour = [];

      for(var i in data) {
        pedCount.push(data[i].pedCount);
        cycCount.push(data[i].cycCount);
        hour.push(data[i].hour);
      }

      var chartdata={
      	labels: hour,
      	datasets : [
      		{
      			label: 'Pedestrian',
      			backgroundColor:'#ff6666',
            borderColor:'#ff6666',
            fill:false,
            lineTension:0,
            radius:2.5,
            borderWidth:2,
            data: pedCount
      		},
          {
            label: 'Cyclist',
            backgroundColor:'#66ccff',
            borderColor:'#66ccff',
            fill:false,
            lineTension:0,
            radius:2.5,
            borderWidth:2,
            data: cycCount
          }
      	]
      };

      var chart = $("#mainChart");

      var myChart = new Chart(chart, {
      	type:'line',
      	data: chartdata,      	
      });
  },
  error: function(data){
  	console.log(data);
  }
	});
});
