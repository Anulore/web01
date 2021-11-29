function send(url) {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', url);
	xhr.send();
	xhr.onload = () => {
		if (xhr.status != 200) {
			alert(xhr.status + " " + xhr.statusText);
			return;
		}
		if (url !== "main.php?delete=true") {
			let response = JSON.parse(xhr.responseText);
			$('.result_table tbody tr').remove();
			response.forEach(object => {
				let tableRow = "<tr>";
				for (let key in object) {
					if (object[key] == "NO")
						tableRow += `<td class="false">${object[key]}</td>`;
					else if (object[key] == "YES")
						tableRow += `<td class="true">${object[key]}</td>`;
					else
						tableRow += `<td>${object[key]}</td>`;
				}
				tableRow += "</tr>";
				$('.result_table tbody').append(tableRow);
			}
			);
		}
	};
}

function reload() {
	send("main.php?reload=true");
}

function clearHistory() {
	$('.result_table tbody tr').remove();
	send("main.php?delete=true");
}

function checkX() {
	if (x.trim() === "") {
		fieldX.textContent = "Поле X должно быть заполнено";
		return false;
	}
	x = x.trim();
	x = x.substring(0, 10).replace(',', '.');
	if (!(x && !isNaN(x))) {
		fieldX.textContent = "X должен быть числом!";
		return false;
	}
	if (x <= -5 || x >=5) {
		fieldX.textContent = "X должен принадлежать промежутку: (-5; 5)!";
		return false;
	}
	return true;
}

function checkY () {
	let result = false;
	yButtons = document.getElementsByName("y");
	yButtons.forEach(button => {
		if (button.classList.contains("pressed-y")) {
			result = true;
			return;
		} 
	});
	if (!result)
		fieldY.textContent = "Значение Y должно быть выбрано";
	return result;
}

function checkR() {
	let result = false;
	rButtons = document.getElementsByName("r");
	rButtons.forEach(button => {
		if (button.classList.contains("pressed-r")) {
			result = true;
			return;
		}
	});
	if (!result)
		fieldR.textContent = "Значение R должно быть выбрано";
	return result;
}

function submit() {
	fieldX.textContent = "";
	fieldY.textContent = "";
	fieldR.textContent = "";

	x = document.querySelector('input[name="x"]').value;
	if (checkX() && checkY() && checkR()) {
		y = document.querySelector('input[name="y"].pressed-y').value;
		r = document.querySelector('input[name="r"].pressed-r').value;
		send(`main.php?x=${x}&y=${y}&r=${r}`);
	}
}

let x, y, r;

let fieldX = document.getElementById("fieldX");
let fieldY = document.getElementById("fieldY");
let fieldR = document.getElementById("fieldR");

reload();

$('#input-y :button').on("click", function () {
	$("input[type='button']").removeClass("pressed-y");
	$(this).addClass("pressed-y");
});

$('#input-r :button').on("click", function () {
	$("input[type='button']").removeClass("pressed-r");
	$(this).addClass("pressed-r");
});

document.getElementById('send').addEventListener('click', submit);
document.getElementById('clear').addEventListener('click', clearHistory);