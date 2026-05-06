document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const msgDiv = document.getElementById('message');
    const itemsList = document.getElementById('items-list');

    // --- 1. FUNCIÓN PARA CARGAR ITEMS (GET) ---
    async function loadItems(query = '') {
        const loader = document.getElementById('loading-indicator');
        
        // Verificamos que el loader exista antes de usarlo
        if (loader) loader.style.display = 'block';
        itemsList.innerHTML = ''; 

        try {
            // USAMOS SIEMPRE LA RUTA SIN /public POR EL .HTACCESS DE RAÍZ
            const url = query 
                ? `/AuraTerra/items?q=${encodeURIComponent(query)}` 
                : '/AuraTerra/items';

            const response = await fetch(url);
            if (!response.ok) throw new Error("Error en la respuesta");
            
            const result = await response.json();
            if (loader) loader.style.display = 'none';

            if (result.ok) {
                if (result.items.length === 0) {
                    itemsList.innerHTML = '<p class="no-results">No se encontraron items para tu búsqueda.</p>';
                } else {
                    renderItems(result.items);
                }
            }
        } catch (error) {
            if (loader) loader.style.display = 'none';
            itemsList.innerHTML = '<p class="error">Error al conectar con la API.</p>';
            console.error(error);
        }
    }

    // --- 2. FUNCIÓN PARA DIBUJAR LA LISTA ---
    function renderItems(items) {
        let html = '<ul>';
        items.forEach(i => {
            html += `<li><strong>${i.name}</strong> - Cantidad: ${i.quantity}</li>`;
        });
        html += '</ul>';
        itemsList.innerHTML = html;
    }

    // --- 3. EVENTOS DE BÚSQUEDA ---
    const searchBtn = document.getElementById('search-button');
    if (searchBtn) {
        searchBtn.addEventListener('click', () => {
            const query = document.getElementById('search-input').value;
            loadItems(query);
        });
    }

    // Carga inicial
    loadItems();

    // --- 4. LÓGICA DEL SUBMIT (POST) ---
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = { name: formData.get('name'), qty: formData.get('qty') };

        try {
            // CORRECCIÓN: Quitamos el /public de la ruta
            const response = await fetch('/AuraTerra/items', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            msgDiv.style.display = 'block';
            
            if (response.status === 201) {
                msgDiv.style.backgroundColor = '#d4edda';
                msgDiv.style.color = '#155724';
                msgDiv.textContent = "✅ ¡Guardado con éxito!";
                form.reset();
                loadItems(); // Recarga la lista automáticamente
            } else {
                msgDiv.style.backgroundColor = '#f8d7da';
                msgDiv.style.color = '#721c24';
                if (result.errors && result.errors.length > 0) {
                    msgDiv.innerHTML = "❌ " + result.errors.join('<br> ❌ '); 
                } else {
                    msgDiv.textContent = "❌ Error desconocido.";
                }
            }
        } catch (err) {
            msgDiv.style.display = 'block';
            msgDiv.textContent = "⚠️ Error de comunicación.";
        }
    });
});