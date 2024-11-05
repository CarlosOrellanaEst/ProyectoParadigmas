
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Búsqueda de Actividades</title>
  <style>
    /* Estilos básicos para los resultados y las entradas de restricciones */
    .activity-result { margin-top: 15px; border: 1px solid #ddd; padding: 10px; }
    .restriction-input { margin-top: 10px; display: flex; align-items: center; }
    .restriction-input label { margin-right: 10px; }
    .remove-btn, .add-btn { margin-left: 10px; cursor: pointer; }
    .remove-btn { color: red; }
    .add-btn { color: green; }
  </style>
</head>
<body>
  <h1>Buscar Actividades</h1>

  <!-- Formulario de búsqueda de actividades -->
  <form id="searchForm">
    <!-- Entrada para el nombre de la actividad -->
    <label for="activityName">Nombre de la Actividad:</label>
    <input type="text" id="activityName" name="activityName">
    <button type="button" onclick="loadAttributes()">Cargar Atributos</button>
    
    <!-- Div para contener los atributos seleccionables -->
    <div id="attributeSelector" style="margin-top: 20px;"></div>
    <button type="button" onclick="filterActivities()">Buscar Actividades</button>
  </form>

  <!-- Div para mostrar los resultados de búsqueda -->
  <div id="results" style="margin-top: 20px;"></div>

  <script>
    // Función para cargar todas las actividades desde la base de datos
    async function fetchActivities() {
      try {
        const response = await fetch('getAllActivities.php'); // Ruta del archivo PHP que obtiene todas las actividades
        if (!response.ok) throw new Error("Error en la respuesta del servidor");
        return response.json();
      } catch (error) {
        console.error("Error al obtener actividades:", error);
        return [];
      }
    }

    // Cargar atributos únicos de las actividades basadas en el nombre
    async function loadAttributes() {
      const activityName = document.getElementById('activityName').value;
      const attributeSet = new Set();

      document.getElementById('attributeSelector').innerHTML = ''; // Limpiar el selector de atributos

      const activities = await fetchActivities(); // Obtiene las actividades desde la base de datos

      // Recorre las actividades y selecciona las que coinciden con el nombre ingresado
      activities.forEach(activity => {
        if (activity.name.toLowerCase() === activityName.toLowerCase()) {
          // Agrega los atributos únicos al Set
          Object.keys(activity.attributes).forEach(attr => attributeSet.add(attr));
        }
      });

      // Si no hay atributos para el nombre dado, muestra un mensaje
      if (attributeSet.size === 0) {
        document.getElementById('attributeSelector').innerHTML = '<p>No se encontraron atributos para este nombre de actividad.</p>';
        return;
      }

      // Div para contener los inputs de restricciones de atributos
      const attributeSelectorDiv = document.createElement('div');
      attributeSelectorDiv.id = 'restrictionInputs';
      document.getElementById('attributeSelector').appendChild(attributeSelectorDiv);

      // Agrega un primer selector de atributos
      addRestrictionInput([...attributeSet]);

      // Botón "+" para agregar más atributos
      const addButton = document.createElement('button');
      addButton.textContent = '➕';
      addButton.className = 'add-btn';
      addButton.type = 'button';
      addButton.onclick = () => addRestrictionInput([...attributeSet]);
      document.getElementById('attributeSelector').appendChild(addButton);
    }

    // Función para agregar un nuevo selector de atributos y un input de restricción
    function addRestrictionInput(attributes) {
      const restrictionInputsDiv = document.getElementById('restrictionInputs');

      // Limita el número de atributos a 5
      if (restrictionInputsDiv.children.length >= 5) {
        alert('Solo puedes agregar hasta 5 atributos');
        return;
      }

      // Crea el select para elegir un atributo
      const attributeSelect = document.createElement('select');
      attributeSelect.classList.add('attributeSelect');
      attributes.forEach(attr => {
        const option = document.createElement('option');
        option.value = attr;
        option.text = attr;
        attributeSelect.appendChild(option);
      });

      // Div para contener el selector y el input de restricción
      const inputDiv = document.createElement('div');
      inputDiv.classList.add('restriction-input');

      // Input para ingresar la restricción del atributo
      const restrictionInput = document.createElement('input');
      restrictionInput.type = 'text';
      restrictionInput.placeholder = `Ingrese restricción para ${attributeSelect.value}`;
      restrictionInput.className = 'restriction';

      // Cambia el placeholder del input según el atributo seleccionado
      attributeSelect.onchange = () => {
        restrictionInput.placeholder = `Ingrese restricción para ${attributeSelect.value}`;
      };

      // Botón "❌" para eliminar el atributo/restricción seleccionado
      const removeBtn = document.createElement('span');
      removeBtn.textContent = '❌';
      removeBtn.className = 'remove-btn';
      removeBtn.onclick = () => restrictionInputsDiv.removeChild(inputDiv);

      // Agrega todos los elementos al div
      inputDiv.appendChild(attributeSelect);
      inputDiv.appendChild(restrictionInput);
      inputDiv.appendChild(removeBtn);

      // Añade el div de entrada de restricción al contenedor principal
      restrictionInputsDiv.appendChild(inputDiv);
    }

    // Filtra las actividades según las restricciones de atributos
    async function filterActivities() {
      const activityName = document.getElementById('activityName').value.toLowerCase();
      const activities = await fetchActivities();

      // Obtiene las selecciones de atributos y sus restricciones
      const attributeSelectors = Array.from(document.getElementsByClassName('attributeSelect'));
      const restrictions = attributeSelectors.reduce((acc, select, index) => {
        const attr = select.value;
        const restrictionValue = document.getElementsByClassName('restriction')[index].value;
        if (attr && restrictionValue) acc[attr] = restrictionValue;
        return acc;
      }, {});

      // Filtra las actividades que coincidan con el nombre y calcula un puntaje de similitud
      const filteredActivities = activities
        .filter(activity => activity.name.toLowerCase() === activityName)
        .map(activity => {
          let similarityScore = 0;

          // Calcula el puntaje de similitud comparando los atributos con las restricciones
          Object.keys(restrictions).forEach(attr => {
            if (restrictions[attr] && activity.attributes[attr] != null) {
              const restrictionValue = isNaN(restrictions[attr]) ? restrictions[attr].toLowerCase() : parseFloat(restrictions[attr]);
              const attrValue = isNaN(activity.attributes[attr]) ? activity.attributes[attr].toLowerCase() : activity.attributes[attr];

              similarityScore += (restrictionValue === attrValue) ? 2 : Math.abs(restrictionValue - attrValue) <= 5 ? 1 : 0;
            }
          });

          // Devuelve la actividad junto con el puntaje de similitud
          return { ...activity, similarityScore };
        })
        .sort((a, b) => b.similarityScore - a.similarityScore); // Ordena según el puntaje de similitud

      displayResults(filteredActivities);
    }

    // Muestra los resultados de búsqueda
    function displayResults(activities) {
      const resultsDiv = document.getElementById('results');
      resultsDiv.innerHTML = ''; // Limpia resultados anteriores

      if (activities.length === 0) {
        resultsDiv.innerHTML = '<p>No se encontraron actividades.</p>';
        return;
      }

      activities.forEach(activity => {
        const div = document.createElement('div');
        div.className = 'activity-result';
        div.innerHTML = `
          <h2>${activity.name} (Puntaje: ${activity.similarityScore})</h2>
          <p><strong>URL:</strong> ${activity.url}</p>
          <p><strong>Atributos:</strong> ${JSON.stringify(activity.attributes)}</p>
        `;
        resultsDiv.appendChild(div);
      });
    }
  </script>
</body>
</html>
