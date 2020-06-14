<!DOCTYPE html>
<html lang="en" xml:lang="en">
<head>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<title> Plagiarism Checker | Main Page </title>
	<link href = "assets/css/bootstrap.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="highlight/styles/monokai-sublime.css">
	<link rel="stylesheet" href="css/circle.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<script src="highlight/highlight.pack.js"></script>

	<style>
		.center{
			text-align: center;
		}
	</style>
	

	<script>
	function validateUpload()
	{
		if(document.getElementById('file').value == "")
		{
			alert("Seleccione un documento.");
			return false;
		}
		return true;
	}
	</script>
</head>
<body>
	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
	<!-- Brand -->
		<a class="navbar-brand" href="#">Comparardor</a>
	</nav> 

	<br>

	<div class="container center" >
		<h1>Comprobador de plagio</h1>
		<p>Un software que permite indicar el porcentaje de similitud entre dos codigos.</p>
	</div> 

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<div class="jumbotron">
					<h3 class="center">Codigo 1</h3>
					<p>Cargar un documento uno</p>
					<form>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="codigo1">
							<label class="custom-file-label" for="codigo1">Seleccione documento 1</label>
						</div>
					</form>
					<br>
					<div class="form-group">
						<label for="codigo1A">Codigo fuente</label>
						<pre class="language-c" id="codigo1A"></pre>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="jumbotron">
					<h3 class="center">Codigo 2</h3>
					<p>Cargar un documento dos</p>
					<form>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="codigo2">
							<label class="custom-file-label" for="codigo2">Seleccione documento 2</label>
						</div>
					</form>
					<br>
					<div class="form-group">				
						<label for="codigo2A">Codigo fuente</label>
						<pre class="language-c" id="codigo2A"></pre>
					</div>
				</div>
			</div>
			
		</div>
		<div class="row">
			<div class="col-sm-12 center">
				<button class="btn btn-success" onclick="comparar()">Comparar</button>
			</div>
		</div>
	</div>

	<br>
	<br>
	<br>
	<br>
	<br>

	<script>
		var codigo1 = "";
		var codigo2 = "";

		function leerArchivo(id) {
			var archivo = id.target.files[0];
			if (!archivo) {
				return;
			}
			var lector = new FileReader();
			lector.onload = function(e) {
				var contenido = e.target.result;
				mostrarContenido(contenido,id.target.id);
			};
			
			lector.readAsText(archivo);
			
		}

		function mostrarContenido(contenido, id) {
			console.log(id)
			var elemento = document.getElementById(id+'A');

			if(id == 'codigo1')
			{
				codigo1 = contenido;
			}
			else
			{
				codigo2 = contenido;
			}
			
			contenido = contenido.replaceAll("<", "&#60");
			contenido = contenido.replaceAll(">", "&#62");
			
			elemento.innerHTML = contenido;
			
			document.querySelectorAll('pre').forEach((block) => {
				hljs.highlightBlock(block);
			});
		}

		document.getElementById('codigo2')
		.addEventListener('change', leerArchivo, false);
		document.getElementById('codigo1')
		.addEventListener('change', leerArchivo, false);

		function comparar(){

			$.post( "comparar.php",{archivo1: codigo1, archivo2: codigo2}, function( data ) {

				mensaje = $.dialog({
					title: 'Verificando codigos',
					content: '<div class="clearfix">\
								<div class="c100 p0" id="progresoCircular">\
									<span id="progreso">100%</span>\
									<div class="slice">\
										<div class="bar"></div>\
										<div class="fill"></div>\
									</div>\
								</div>\
							</div>\
							',
					type: 'blue',
					typeAnimated: true,
				});

				contador = 0;
				$('#progreso').html("0%");
				$('#progresoCircular').removeClass("p0");
				$('#progresoCircular').addClass("p0");
				var ciclo = setInterval(function(){
					contador+=5;

					$('#progreso').html(contador+"%");
					$('#progresoCircular').removeClass("p"+(contador-5));
					$('#progresoCircular').addClass("p"+contador);

					if(contador >= 100)
					{
						clearInterval(ciclo);
						mensaje.close()
						mostrar(data);
					}
				},200);
			});
		}

		function mostrar(data){

			if(data.error)
				$.confirm({
					title: 'Error',
					content: data.mensaje,
					type: 'red',
					typeAnimated: true,
				});
			else{
				if(data.similitud<=25)
					color = "green";
				else if(data.similitud<=50)
					color = "orange";
				else
					color = "red";

				$.confirm({
					icon: 'fa fa-spinner fa-spin',
					title: 'Porcentaje',
					content: data.mensaje,
					type: color,
					typeAnimated: true,
				});
			}
		}
	</script>


    
</body>
	<script src = "assets/js/jquery.js"></script>
	<script src = "assets/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
</html>