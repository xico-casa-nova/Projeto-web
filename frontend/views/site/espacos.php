<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\helpers\StringHelper;
use frontend\assets\AppAsset;

$cidade = $cidade ?? '';
$capacidadeMin = (int)($capacidadeMin ?? 0);
$locais = $locais ?? [];

$this->title = 'Espaços';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/espacos.css', [ // Adiciona o arquivo CSS específico para esta página
    'depends' => [AppAsset::class],
]);
$this->params['meta_description'] = 'Explore os espaços disponíveis na EventLink e encontre o local ideal para o seu evento.';
?>

<!--parte de cima, titulo e descrição da página-->

<div class="site-espacos py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="card-location d-block">Catálogo de Propriedades</span>
            <h1 class="display-5 font-serif fw-normal mb-3" style="color: var(--text-dark);">
                Encontre o espaço ideal
            </h1>
            <p class="mx-auto mb-0" style="max-width: 650px; color: var(--text-muted);">
                Explore a nossa seleção de salões, quintas e espaços exclusivos preparados para acolher o seu próximo evento.
            </p>
        </div>

        <!---->

        <!--Filtros de busca-->
        <div class="p-4 mb-5 rounded" style="background-color: var(--card-bg); border: 1px solid rgba(0, 0, 0, 0.05);">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="filtro-cidade" class="form-label fw-semibold small" style="color: var(--text-dark);">Cidade</label>
                    <input type="text" id="filtro-cidade" name="cidade" class="form-control" placeholder="Ex: Lisboa, Porto, Leiria..."
                        value="<?= Html::encode($cidade) ?>"> <!--É o método de segurança do Yii2 que converte caracteres especiais (<, >, ", ', &) em entidades HTML seguras-->
                </div>
                <div class="col-md-4">
                    <label for="filtro-capacidade" class="form-label fw-semibold small" style="color: var(--text-dark);">Capacidade mínima (pessoas)</label>
                    <input type="number" id="filtro-capacidade" name="capacidade" class="form-control" min="0" placeholder="Ex: 100"
                        value="<?= $capacidadeMin > 0 ? Html::encode((string)$capacidadeMin) : '' ?>">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-burgundy w-100">Pesquisar</button>
                    <?php if ($cidade !== '' || $capacidadeMin > 0): ?>
                        <?= Html::a('Limpar', ['site/espacos'], ['class' => 'btn btn-outline-secondary']) ?>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!---->

        <?php if (empty($locais)): ?> <!--empty valida se não houverem locais ou se não corresponderem à pesquisa-->
            <div class="text-center py-5 rounded" style="background-color: var(--card-bg); border: 1px solid rgba(0, 0, 0, 0.05);">
                <h3 class="h5 font-serif mb-2" style="color: var(--text-dark);">Nenhum espaço encontrado</h3>
                <p class="mb-3" style="color: var(--text-muted);">Não existem espaços ativos que correspondam aos critérios selecionados.</p>
                <?php if ($cidade !== '' || $capacidadeMin > 0): ?>
                    <?= Html::a('Ver todos os espaços', ['site/espacos'], ['class' => 'link-burgundy']) ?><!--hiperligação para ver todos os locais-->
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($locais as $local): ?> <!--foreach para percorrer os locais encontrados-->
                    <?php
                    $imagemPrincipal = $local->foto_capa_url ?: ($local->localFotos[0]->foto_url ?? null);
                    $totalFotos = count($local->localFotos);
                    ?>
                    <div class="col-md-4">
                        <div class="property-card">
                            <div>
                                <?php if ($imagemPrincipal): ?>
                                    <div class="position-relative mb-3">
                                        <img
                                            src="<?= Html::encode($imagemPrincipal) ?>"
                                            alt="<?= Html::encode($local->nome) ?>"
                                            class="w-100 rounded"
                                            style="height: 200px; object-fit: cover;">
                                        <?php if ($totalFotos > 0): ?>
                                            <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 end-0 m-2">
                                                +<?= $totalFotos ?> <?= $totalFotos === 1 ? 'foto' : 'fotos' ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="card-location"><?= Html::encode($local->cidade) ?></div>
                                <h3 class="card-title font-serif"><?= Html::encode($local->nome) ?></h3>
                                <p class="card-desc mb-0">
                                    <?= Html::encode(StringHelper::truncate($local->descricao ?? 'Sem descrição detalhada.', 110)) ?>
                                </p>
                            </div>

                            <div class="card-price d-flex justify-content-between align-items-baseline">
                                <div>
                                    <?= Yii::$app->formatter->asCurrency($local->preco_aluguer_base, 'EUR') ?> <span>&bull; até <?= Html::encode((string)$local->capacidade_total) ?></span>
                                </div>
                                <?= Html::a('Ver detalhes &rarr;', ['site/espaco-detalhe', 'id' => $local->id], ['class' => 'link-burgundy']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>