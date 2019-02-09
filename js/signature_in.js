function signatureCapture() {

	var parent = document.getElementById("canvas");
	parent.childNodes[0].nodeValue = "";

	var canvasArea = document.createElement("canvas");
	canvasArea.setAttribute("id", "newSignature");
	parent.appendChild(canvasArea);

	var canvas = document.getElementById("newSignature");
	var context = canvas.getContext("2d");

	if (!context) {
		throw new Error("Failed to get canvas' 2d context");
	}

	screenwidth = screen.width;

	if (screenwidth < 480) {
		canvas.width = screenwidth - 8;
		canvas.height = (screenwidth * 0.63);
	} else {
		canvas.width = 575;
		canvas.height = 150;
	}

	context.fillStyle = "#fff";
	context.strokeStyle = "#444";
	context.lineWidth = 1.2;
	context.lineCap = "round";

	context.fillRect(0, 0, canvas.width, canvas.height);

	context.fillStyle = "#337ab7";
	context.strokeStyle = "#337ab7";
	context.lineWidth = 3;
	context.moveTo((canvas.width * 0.042), (canvas.height * 0.7));
	context.lineTo((canvas.width * 0.958), (canvas.height * 0.7));
	context.stroke();

	context.fillStyle = "#fff";
	context.strokeStyle = "#000";

	var disableSave = true;
	var pixels = [];
	var cpixels = [];
	var xyLast = {};
	var xyAddLast = {};
	var calculate = false;
	//functions
	{
		function remove_event_listeners() {
			canvas.removeEventListener('mousemove', on_mousemove, false);
			canvas.removeEventListener('mouseup', on_mouseup, false);
			canvas.removeEventListener('touchmove', on_mousemove, false);
			canvas.removeEventListener('touchend', on_mouseup, false);

			document.body.removeEventListener('mouseup', on_mouseup, false);
			document.body.removeEventListener('touchend', on_mouseup, false);
		}

		function get_board_coords(e) {
			var x, y;

			if (e.changedTouches && e.changedTouches[0]) {
				var offsety = canvas.offsetTop || 0;
				var offsetx = canvas.offsetLeft || 0;

				x = e.changedTouches[0].pageX - offsetx;
				y = e.changedTouches[0].pageY - offsety;
			} else if (e.layerX || 0 == e.layerX) {
				x = e.layerX;
				y = e.layerY;
			} else if (e.offsetX || 0 == e.offsetX) {
				x = e.offsetX;
				y = e.offsetY;
			}

			return {
				x : x,
				y : y
			};
		};

		function on_mousedown(e) {
			e.preventDefault();
			e.stopPropagation();

			canvas.addEventListener('mousemove', on_mousemove, false);
			canvas.addEventListener('mouseup', on_mouseup, false);
			canvas.addEventListener('touchmove', on_mousemove, false);
			canvas.addEventListener('touchend', on_mouseup, false);

			document.body.addEventListener('mouseup', on_mouseup, false);
			document.body.addEventListener('touchend', on_mouseup, false);

			empty = false;
			var xy = get_board_coords(e);
			context.beginPath();
			pixels.push('moveStart');
			context.moveTo(xy.x, xy.y);
			pixels.push(xy.x, xy.y);
			xyLast = xy;
		};

		function on_mousemove(e, finish) {
			e.preventDefault();
			e.stopPropagation();

			var xy = get_board_coords(e);
			var xyAdd = {
				x : (xyLast.x + xy.x) / 2,
				y : (xyLast.y + xy.y) / 2
			};

			if (calculate) {
				var xLast = (xyAddLast.x + xyLast.x + xyAdd.x) / 3;
				var yLast = (xyAddLast.y + xyLast.y + xyAdd.y) / 3;
				pixels.push(xLast, yLast);
			} else {
				calculate = true;
			}

			context.quadraticCurveTo(xyLast.x, xyLast.y, xyAdd.x, xyAdd.y);
			pixels.push(xyAdd.x, xyAdd.y);
			context.stroke();
			context.beginPath();
			context.moveTo(xyAdd.x, xyAdd.y);
			xyAddLast = xyAdd;
			xyLast = xy;

		};

		function on_mouseup(e) {
			remove_event_listeners();
			disableSave = false;
			context.stroke();
			pixels.push('e');
			calculate = false;
		};

	}//end
	canvas.addEventListener('mousedown', on_mousedown, false);
	canvas.addEventListener('touchstart', on_mousedown, false);
}

function signatureSave() {
	var canvas = document.getElementById("newSignature");
	var dataURL = canvas.toDataURL("image/png");
	document.getElementById("saveSignature").src = dataURL;
};

function signatureClear() {
	var parent = document.getElementById("canvas");
	var child = document.getElementById("newSignature");
	parent.removeChild(child);
	signatureCapture();
}

function signatureSend() {

	var canvas = document.getElementById("newSignature");
	var dataURL = canvas.toDataURL("image/png");
	document.getElementById("saveSignature").src = dataURL;
	var full_name = document.getElementById('full_name').value;
	var company_name = document.getElementById('company_name').value;
	var veh_reg = document.getElementById('veh_reg').value;
	var contact_list = document.getElementById('contact_list').value;
	var get_date = document.getElementById('get_date').value;
	var get_ip = document.getElementById('get_ip').value;
	var image_profile = document.getElementById('image_profile').value;
	
	if (full_name == "")  
{  
alert("Please enter your full name.");
theForm.full_name.focus();  
return false;  
}  
	if (company_name == "")  
{  
alert("Please enter your company name or if none please type in N/A."); 
theForm.company_name.focus(); 
return false;  
}
	if (dataURL == "data:image/png;base64,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")  
{  
alert("Please Sign your Signature.");  
return false;  
} 
	if (dataURL == "data:image/png;base64,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")  
{  
alert("Please Sign your Signature.");  
return false;  
} 
	if (dataURL == "data:image/png;base64,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")  
{  
alert("Please Sign your Signature.");  
return false;  
} 

	var form = document.createElement("form");
	document.getElementsByTagName('body')[0].appendChild(form);
	form.setAttribute("action", "upload_in.php");
	form.setAttribute("enctype", "multipart/form-data");
	form.setAttribute("method", "POST");
	form.setAttribute("target", "_self");
	form.innerHTML = '<input type="text" name="image" value="' + dataURL + '"/>' + '<input type="text" name="get_date" value="' + get_date + '"/>' + '<input type="text" name="get_ip" value="' + get_ip + '"/>' + '<input type="text" name="full_name" value="' + full_name + '"/>' + '<input type="text" name="company_name" value="' + company_name + '"/>' + '<input type="text" name="veh_reg" value="' + veh_reg + '"/>' +'<input type="text" name="contact_list" value="' + contact_list + '"/>' + '<input type="text" name="image_profile" value="' + image_profile + '"/>';
	document.body.appendChild(form);
	form.submit();
}