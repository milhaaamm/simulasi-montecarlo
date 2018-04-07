$(document).ready(function(){
	$("#result2").hide();
	$("#inputtable").submit(function(e) 
	{
		var url1 = "result.php"; // the script where you handle the form input.
		var url2 = "chartloader.php";
		$.ajax({
			   type: "POST",
			   url: url1,
			   data: $("#inputtable").serialize(), // serializes the form's elements.
			   success: function(data)
			   {
				   $("#result1").html(data);
				   $("#result2").load("chartloader.php");
				   $("#result2").show();
			   }
			 });

		e.preventDefault(); // avoid to execute the actual submit of the form.
	});
	
	$("#btnrandom").click(function()
	{
		for(i=1;i<=12;i++)
		{
			$("#BT-pro"+i).val(randombetween(2000,1000));
			$("#AS-pro"+i).val(randombetween(2000,1000));
			$("#RA-pro"+i).val(randombetween(2000,1000));
			$("#BT-per"+i).val(randombetween(2000,1000));
			$("#AS-per"+i).val(randombetween(2000,1000));
			$("#RA-per"+i).val(randombetween(2000,1000));
		}
	});

	$("#dataharga").submit(function(e)
	{
		alert("submitted");
		var url3 = "param.php"; // the script where you handle the form input.
		//var url2 = "result2.php";
		$.ajax({
			   type: "POST",
			   url: url3,
			   data: $("#dataharga").serialize(), // serializes the form's elements.
			   success: function(data)
			   {
			   	if(data == "OK")
				   $("#dataharga :input").attr("disabled",true);
				else
					alert(data);
			   }
			 });
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});
	

	$("#btndefault").click(function()
	{
		$("#bt_harga").val(10000);
		$("#as_harga").val(12000);
		$("#ra_harga").val(13500);
		$("#bt_mesin").val(250);
		$("#as_mesin").val(250);
		$("#ra_mesin").val(250);
	});
	function max(array)
	{
		return Math.max.apply(null, array);
	}
	function min(array)
	{
		return Math.min.apply(null, array);
	}
	
	function randombetween(atas,bawah)
	{
		return Math.floor(Math.random()*bawah)+(atas-bawah);
	}
	function getparametervalue(parametername)
	{
		$.get("getParam.php",{name:parametername},function(data)
		{
			alert(data);
			return data;
		});
	}
});