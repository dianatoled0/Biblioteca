<?= $this->extend('admin/layout') ?>

<?= $this->section('contenido') ?>

<div class="main-header">
    <h2><?= esc($title ?? 'Dashboard') ?></h2>
</div>

<div class="page-grid" style="grid-template-columns: 1fr;">
    
    <div class="card" style="grid-column: span 1;">
        <div class="card-title"> Notificaciones del Sistema (Historial Reciente)</div>
        
        <div class="notifications-list" style="max-height: none; overflow-y: visible; padding-top: 10px;">
            <ul>
                <?php if (!empty($notificaciones)): ?>
                    <?php 
                    foreach ($notificaciones as $notif): 
                        $tipo = $notif['tipo_evento'];
                        $tipo_formateado = esc(strtoupper(str_replace('_', ' ', $tipo)));
                        $color = '#6D28D9'; // Púrpura (Defecto)
                        $icono = '🔹'; 
                        
                        // Determinar el icono y color basado en el tipo de evento
                        switch ($tipo) {
                            case 'pago_creado':
                                $color = '#059669'; // Verde
                                $icono = '💰';
                                break;
                            case 'ingreso_stock':
                                $color = '#F59E0B'; // Amarillo/Naranja
                                $icono = '📦';
                                break;
                            case 'nuevo_usuario':
                                $color = '#3B82F6'; // Azul
                                $icono = '👤';
                                break;
                            case 'pedido_realizado':
                                $color = '#EF4444'; // Rojo/Alerta
                                $icono = '🛒';
                                break;
                            // Puedes añadir más casos aquí
                        }
                    ?>
                        <li style="border-left: 4px solid <?= $color ?>; padding: 10px; margin-bottom: 10px; border-radius: 4px; background-color: #2D374820;">
                            <div style="font-size: 14px;">
                                
                                <span style="font-weight: 700; color: <?= $color ?>;">
                                    <?= $icono ?> <?= $tipo_formateado ?>:
                                </span> 
                                <?= esc($notif['mensaje']) ?>
                            </div>
                            
                            <div style="font-size: 11px; color: #9da9bc; margin-top: 3px; text-align: right;">
                                Registrado el <?= date('d/m/Y', strtotime($notif['created_at'])) ?> a las <?= date('H:i', strtotime($notif['created_at'])) ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li style="text-align: center; color: #A0AEC0; padding: 20px; border: 1px dashed #4A5568; border-radius: 4px;">
                        No hay notificaciones recientes en este momento. ¡El sistema está estable!
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        
        </div>
    
    </div>

<?= $this->endSection() ?>
