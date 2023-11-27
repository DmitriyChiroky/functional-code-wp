	/* 
	.data-siblings
	*/
	function wcl_data_siblings() {
		if (document.querySelector('.data-siblings')) {
			let section = document.querySelector('.data-siblings');

			section.querySelectorAll('.data-siblings-delete-button').forEach(element => {
				element.addEventListener('click', function (e) {
					e.stopPropagation()
					this.closest('.data-siblings-item').remove()
				})
			});

			// New Task

			const taskInput = document.getElementById("brothers_and_sisters");
			const addTaskButton = document.getElementById("brothers_and_sisters_add");
			const taskList = document.getElementById("brothers_and_sisters_list");

			addTaskButton.addEventListener("click", function () {
				const taskText = taskInput.value.trim();

				if (taskText !== "") {
					const taskItem = document.createElement("li");
					const taskSpan = document.createElement("span");
					taskSpan.textContent = taskText;
					taskItem.classList.add("data-siblings-item");

					const deleteButton = document.createElement("div");
					deleteButton.textContent = "Видалити";
					deleteButton.classList.add("data-siblings-delete-button");

					deleteButton.addEventListener("click", function (e) {
						e.stopPropagation()
						this.closest('.data-siblings-item').remove()
					});

					taskItem.appendChild(taskSpan);
					taskItem.appendChild(deleteButton);
					taskList.appendChild(taskItem);
					taskInput.value = "";
				}
			});
		}

	}