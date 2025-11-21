<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package mediapilote
 */

get_header();
?>

<main class="mt-50">
    <div class="container align80">

        <!-- Section 404 principale -->
        <div class="error-404-section">
            <div class="error-404-content text-center">
                
                <!-- Numéro d'erreur stylisé -->
                <div class="error-number">
                    <span class="size-xl ">4</span>
                    <span class="size-xl ">0</span>
                    <span class="size-xl ">4</span>
                </div>
                
                <!-- Message d'erreur -->
                <div class="error-message">
                    <h1 class="size-semi  mt-30">Oups ! Page introuvable</h1>
                    <p class="size-normal text-grey mt-20">Désolé, nous n'avons pas trouvé ce que vous cherchez. Cette page a peut-être été déplacée, supprimée ou n'a jamais existé.</p>
                </div>
                

                
                <!-- Actions -->
                <div class="error-actions mt-50">
                    <div class="d-flex y-center space-around flex-wrap">
                        <a href="<?php echo home_url(); ?>" class="btn btn-primary btn-large">
                            <span class="d-flex y-center">
                                <svg class="icon icon-20 mr-10" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                                Retour à l'accueil
                            </span>
                        </a>
                        

                    </div>
                </div>
                
                <!-- Suggestions de navigation -->
                <div class="error-suggestions mt-60 d-flex flex-row">
                        
                        <div class="suggestion-item">
                            <div class="suggestion-icon background-light-primary rounded">
                                <svg class="icon icon-40 text-primary" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <h4 class="size-subnormal text-dark-primary mt-20">Nos produits</h4>
                            <p class="size-small text-grey">Découvrez notre gamme complète</p>
                            <a href="<?php echo home_url('/nos-produits/'); ?>" class="btn btn-small btn-primary mt-15">Voir les produits</a>
                        </div>
                        
                        <div class="suggestion-item">
                            <div class="suggestion-icon background-light-primary rounded">
                                <svg class="icon icon-40 text-primary" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            <h4 class="size-subnormal text-dark-primary mt-20">Nous contacter</h4>
                            <p class="size-small text-grey">Notre équipe est là pour vous aider</p>
                            <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-small btn-primary mt-15">Contact</a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</main>

<style>
/* Styles spécifiques à la page 404 */
.error-404-section {
    padding: 80px 0;
    min-height: 70vh;
    display: flex;
    align-items: center;
}

.error-404-content {
    max-width: 800px;
    margin: 0 auto;
}

.error-number {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    color: #FFE04B;
}

.error-number span {
    animation: bounce 2s infinite;
}

.error-number span:nth-child(2) {
    animation-delay: 0.1s;
}

.error-number span:nth-child(3) {
    animation-delay: 0.2s;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-20px);
    }
    60% {
        transform: translateY(-10px);
    }
}

.error-message h1 {
    margin-bottom: 20px;
    font-family: 'Figtree', sans-serif!important;
    text-transform: uppercase;
    font-weight: 600;

}

.error-message p {
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.error-actions {
    margin-bottom: 40px;
}

.error-actions .btn {
    margin: 10px;
    min-width: 200px;
}

.error-actions .btn span {
    gap: 8px;
}

.error-suggestions {
    border-radius: 20px;
    gap: 50px;
}

.suggestions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.suggestion-item {
    text-align: center;
    padding: 30px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.suggestion-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.suggestion-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.suggestion-item h4 {
    margin-bottom: 10px;
}

.suggestion-item p {
    margin-bottom: 20px;
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 768px) {
    .error-404-section {
        padding: 40px 0;
    }
    
    .error-number {
        gap: 5px;
    }
    
    .error-number span {
        font-size: 4rem !important;
        line-height: 4rem !important;
    }
    
    .error-actions .btn {
        min-width: 100%;
        margin: 10px 0;
    }
    
    .suggestions-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .error-suggestions {
        padding: 30px 20px;
        flex-direction: column!important;
    }
}

@media (max-width: 480px) {
    .error-404-content {
        padding: 0 20px;
    }
    
    .error-number span {
        font-size: 3rem !important;
        line-height: 3rem !important;
    }
}
</style>

<?php
get_footer();
?>