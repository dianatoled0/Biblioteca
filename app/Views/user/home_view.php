<?= $this->extend('layoutuser/layout_user') ?>

<?= $this->section('content') ?>

<header class="main-header">
    <h2>Bienvenido, <?= esc($nombreUsuario) ?> a Melofy</h2>
    <h3>Explora Nuestros Discos</h3>
</header>

<div class="category-filter" id="categoryFilter">
    <button data-id="0" class="active">Todos</button>
    <?php foreach ($categorias as $cat): ?>
        <button data-id="<?= esc($cat['id']) ?>"><?= esc($cat['nom_categoria']) ?></button>
    <?php endforeach; ?>
</div>

<div class="disk-grid" id="allDiscosList">
    <?php if (!empty($allDiscos)): ?>
        <?php foreach ($allDiscos as $disco): ?>
            <div class="disk-card" data-id="<?= esc($disco['id']) ?>">
                <h4><?= esc($disco['titulo']) ?></h4>
                <p>Artista: <?= esc($disco['artista']) ?></p>
                <p>Categoría: <?= esc($disco['nom_categoria'] ?? 'N/A') ?></p> 
                <p class="price">Precio: Q <?= number_format(esc($disco['precio_venta']), 2) ?></p>
                
                <button class="btn-primary add-to-cart-btn" data-id="<?= esc($disco['id']) ?>" data-stock="<?= esc($disco['stock']) ?>">
                    Comprar
                </button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay discos disponibles.</p>
    <?php endif; ?>
</div>

<?= $this->include('user/_carrito_modal') ?>

<script>
    const BASE_URL = '<?= base_url('usuario') ?>';

    $(document).ready(function() {
        // Lógica de filtro al hacer clic
        $('#categoryFilter button').on('click', function() {
            $('#categoryFilter button').removeClass('active');
            $(this).addClass('active');
            filterDiscos($(this).data('id'));
        });

        // Lógica para añadir al carrito
        $('#allDiscosList').on('click', '.add-to-cart-btn', function() {
            addToCarrito($(this).data('id'), 1); 
        });

        updateCarritoDisplay();
    });

    /**
     * Función AJAX para obtener y actualizar el grid de discos.
     */
    function filterDiscos(categoryId) {
        $.ajax({
            // CLAVE: La URL llama al método del controlador
            url: BASE_URL + '/ajax/discos/' + categoryId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    renderDiscos(response.discos);
                }
            },
            error: function() {
                // Si ves este alert, el problema fue en el servidor (el controlador)
                alert('Error al cargar los discos.');
            }
        });
    }

    /**
     * Renderiza el HTML de los discos obtenidos por AJAX
     */
    function renderDiscos(discos) {
        let html = '';
        if (discos.length > 0) {
            discos.forEach(function(disco) {
                html += `
                    <div class="disk-card" data-id="${disco.id}">
                        <h4>${disco.titulo}</h4>
                        <p>Artista: ${disco.artista}</p>
                        <p>Categoría: ${disco.nom_categoria}</p>
                        <p class="price">Precio: Q ${parseFloat(disco.precio_venta).toFixed(2)}</p>
                        <button class="btn-primary add-to-cart-btn" data-id="${disco.id}" data-stock="${disco.stock}">
                            Comprar
                        </button>
                    </div>
                `;
            });
        } else {
            html = '<p style="grid-column: 1 / -1;">No se encontraron discos en esta categoría.</p>';
        }
        $('#allDiscosList').html(html);
    }
</script>

<?= $this->endSection() ?>