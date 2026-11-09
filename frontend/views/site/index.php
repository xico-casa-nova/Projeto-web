<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'EventLink - A sua festa merece um lugar à altura';
$this->params['meta_description'] = 'EventLink é a sua plataforma de referência para encontrar propriedades exclusivas para festas, casamentos e eventos. Descubra locais únicos com preços por pessoa e serviços personalizados, a partir de 45,00 € por convidado. Explore as nossas propriedades e torne o seu evento inesquecível.';
$this->params['meta_keywords'] = 'yii, yii2, php, framework, web application, high-performance';
?>
<!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="col-lg-7 text-white">
                <span class="hero-subtitle d-block mb-3">FESTAS &bull; CASAMENTOS &bull; EVENTOS</span>
                <h1 class="hero-title font-serif mb-4">A sua festa merece um lugar à altura.</h1>
                <p class="hero-description mb-4">
                    Propriedades exclusivas da EventLink, com preço por pessoa e serviços à medida. A partir de 45,00 € por convidado.
                </p>
                <a href="#propriedades" class="btn btn-burgundy">Ver propriedades</a>
            </div>
        </div>
    </section>

    <!-- Secção Propriedades -->
    <section id="propriedades" class="py-5">
        <div class="container py-4">
            
            <!-- Cabeçalho da Secção -->
            <div class="d-flex justify-content-between align-items-baseline mb-4">
                <h2 class="font-serif fs-2 fw-normal mb-0">As nossas propriedades</h2>
                <a href="#" class="link-burgundy">Ver todas &rarr;</a>
            </div>

            <!-- Grelha de Cards Bootstrap -->
            <div class="row g-4">
                
                <!-- Card 1 -->
                <div class="col-md-4">
                    <div class="property-card">
                        <div>
                            <div class="card-location">COMPORTA</div>
                            <h3 class="card-title font-serif">Casa da Praia</h3>
                            <p class="card-desc">Espaço à beira-mar com esplanada e pôr do sol.</p>
                        </div>
                        <div class="card-price">
                            60,00 € / pessoa <span>&bull; até 80</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-4">
                    <div class="property-card">
                        <div>
                            <div class="card-location">DOURO</div>
                            <h3 class="card-title font-serif">Quinta do Vale</h3>
                            <p class="card-desc">Quinta rodeada de vinhas com salão envidraçado e jardim.</p>
                        </div>
                        <div class="card-price">
                            45,00 € / pessoa <span>&bull; até 200</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-4">
                    <div class="property-card">
                        <div>
                            <div class="card-location">SINTRA</div>
                            <h3 class="card-title font-serif">Solar Antigo</h3>
                            <p class="card-desc">Solar do século XVIII com pátio interior e capela.</p>
                        </div>
                        <div class="card-price">
                            55,00 € / pessoa <span>&bull; até 150</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
