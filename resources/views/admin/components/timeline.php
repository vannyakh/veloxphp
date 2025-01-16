<?php
/**
 * @var array $items Timeline items
 */
?>
<div class="timeline">
    <?php foreach ($items as $date => $events): ?>
        <div class="time-label">
            <span class="bg-primary"><?= $date ?></span>
        </div>
        
        <?php foreach ($events as $event): ?>
            <div>
                <i class="fas fa-<?= $event['icon'] ?? 'circle' ?> bg-<?= $event['type'] ?? 'info' ?>"></i>
                <div class="timeline-item">
                    <?php if (isset($event['time'])): ?>
                        <span class="time">
                            <i class="fas fa-clock"></i> <?= $event['time'] ?>
                        </span>
                    <?php endif; ?>

                    <h3 class="timeline-header">
                        <?php if (isset($event['user'])): ?>
                            <a href="#"><?= $event['user'] ?></a>
                        <?php endif; ?>
                        <?= $event['title'] ?>
                    </h3>

                    <?php if (isset($event['body'])): ?>
                        <div class="timeline-body">
                            <?= $event['body'] ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($event['footer'])): ?>
                        <div class="timeline-footer">
                            <?= $event['footer'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
    
    <div>
        <i class="fas fa-clock bg-gray"></i>
    </div>
</div> 