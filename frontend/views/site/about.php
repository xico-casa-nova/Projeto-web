<?php

declare(strict_types=1);

use yii\helpers\Html;

$this->title = 'Sobre Nós';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_description'] = 'Conheça a EventLink, a plataforma dedicada a encontrar e gerir o espaço ideal para o seu evento.';
$this->params['meta_keywords'] = 'eventlink, eventos, espaços, festas, reservas';
?>

<div class="site-about py-5">
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-7">
            <h1 class="display-5 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">
                Sobre a EventLink
            </h1>
            <p class="lead text-body-secondary mb-4">
                A <strong>EventLink</strong> nasceu para simplificar a ligação entre quem procura o cenário perfeito para celebrar e os melhores espaços para eventos.
            </p>
            <p class="text-body-secondary mb-4">
                Seja um casamento, um aniversário, uma conferência empresarial ou um jantar privado, reunimos numa única plataforma a reserva do espaço, a escolha de serviços complementares e uma gestão transparente de todo o processo.
            </p>
            <div class="d-flex flex-wrap gap-3">
                <?= Html::a('Explorar Espaços', ['/local/index'], ['class' => 'btn btn-primary btn-lg px-4']) ?>
                <?= Html::a('Fale Connosco', ['/site/contact'], ['class' => 'btn btn-outline-secondary btn-lg px-4']) ?>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm bg-body-tertiary p-4">
                <h4 class="fw-semibold mb-3" style="font-family: 'Playfair Display', serif;">Porquê escolher a EventLink?</h4>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <li>
                        <strong>Espaços Selecionados</strong>
                        <p class="text-body-secondary small mb-0">Consulte capacidade, localização, fotografias reais e valores base antes de reservar.</p>
                    </li>
                    <li>
                        <strong>Tudo num Só Lugar</strong>
                        <p class="text-body-secondary small mb-0">Associe produtos e serviços extra diretamente ao seu evento sem complicações.</p>
                    </li>
                    <li>
                        <strong>Gestão Clara e Segura</strong>
                        <p class="text-body-secondary small mb-0">Acompanhe o estado do seu evento e toda a faturação associada de forma simples.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4 text-center pt-4 border-top">
        <div class="col-md-4">
            <div class="p-3">
                <h3 class="h5 fw-bold">1. Escolha o Espaço</h3>
                <p class="text-body-secondary small mb-0">Compare lotação, localização e condições para encontrar o ambiente certo.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3">
                <h3 class="h5 fw-bold">2. Personalize o Evento</h3>
                <p class="text-body-secondary small mb-0">Defina o tipo de evento, a data e os produtos necessários à medida da ocasião.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3">
                <h3 class="h5 fw-bold">3. Celebre sem Preocupações</h3>
                <p class="text-body-secondary small mb-0">Acompanhe todos os detalhes e documentos numa área reservada e intuitiva.</p>
            </div>
        </div>
    </div>
</div>
