<x-layouts.app>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Test Modal Bootstrap</h3>
                    </div>
                    <div class="card-body">
                        <p>Cliquez sur le bouton ci-dessous pour tester le modal Bootstrap :</p>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">
                            Launch static backdrop modal
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Ceci est un test de modal Bootstrap.</p>
                                        <p>Vous devriez pouvoir :</p>
                                        <ul>
                                            <li>Cliquer sur le bouton de fermeture (X)</li>
                                            <li>Cliquer sur le bouton "Close"</li>
                                            <li>Cliquer sur le bouton "Understood"</li>
                                            <li>Interagir avec tous les éléments du modal</li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Understood</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Test avec modal permettant la fermeture par Escape et backdrop -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#flexibleModal">
                            Launch flexible modal (Escape + Backdrop)
                        </button>

                        <!-- Modal flexible -->
                        <div class="modal fade" id="flexibleModal" tabindex="-1" aria-labelledby="flexibleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="flexibleModalLabel">Flexible Modal</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Ce modal peut être fermé de plusieurs façons :</p>
                                        <ul>
                                            <li>Touche Escape</li>
                                            <li>Clic sur le backdrop (fond gris)</li>
                                            <li>Bouton de fermeture (X)</li>
                                            <li>Bouton "Close"</li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Instructions de test</h4>
                    </div>
                    <div class="card-body">
                        <h5>Modal Static Backdrop :</h5>
                        <ul>
                            <li>✅ Ne se ferme PAS avec Escape (data-bs-keyboard="false")</li>
                            <li>✅ Ne se ferme PAS en cliquant sur le backdrop (data-bs-backdrop="static")</li>
                            <li>✅ Se ferme uniquement avec les boutons</li>
                        </ul>

                        <h5 class="mt-3">Modal Flexible :</h5>
                        <ul>
                            <li>✅ Se ferme avec Escape (comportement par défaut)</li>
                            <li>✅ Se ferme en cliquant sur le backdrop (comportement par défaut)</li>
                            <li>✅ Se ferme avec les boutons</li>
                        </ul>

                        <div class="alert alert-info mt-3">
                            <strong>Note :</strong> Si les boutons ne répondent pas, vérifiez la console du navigateur
                            (F12) pour voir s'il y a des erreurs JavaScript.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // Debug helper inline
            console.log('🔍 Test Modal Page - Debug activé');

            // Vérifier Bootstrap
            if (typeof bootstrap !== 'undefined') {
                console.log('✅ Bootstrap chargé');
            } else {
                console.error('❌ Bootstrap NON chargé !');
            }

            // Logger tous les clics
            document.addEventListener('click', (e) => {
                console.log('Clic détecté sur:', e.target.tagName, e.target.className);

                if (e.target.hasAttribute('data-bs-toggle')) {
                    console.log('Bouton toggle modal:', e.target.getAttribute('data-bs-target'));
                }

                if (e.target.hasAttribute('data-bs-dismiss')) {
                    console.log('Bouton dismiss modal détecté');
                }
            });

            // Logger les événements de modal
            document.addEventListener('show.bs.modal', (e) => {
                console.log('🟢 Modal opening:', e.target.id);
            });

            document.addEventListener('shown.bs.modal', (e) => {
                console.log('✅ Modal opened:', e.target.id);
                const modal = e.target;
                console.log('Modal z-index:', getComputedStyle(modal).zIndex);
                console.log('Modal pointer-events:', getComputedStyle(modal).pointerEvents);
            });

            document.addEventListener('hide.bs.modal', (e) => {
                console.log('🔴 Modal closing:', e.target.id);
            });

            // Test manuel
            window.forceCloseModal = function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) {
                        bsModal.hide();
                        console.log('Modal fermé manuellement');
                    } else {
                        console.error('Instance Bootstrap Modal non trouvée');
                    }
                }
            };

            console.log('💡 Utilisez forceCloseModal("staticBackdrop") pour fermer manuellement');
        </script>
    @endpush
</x-layouts.app>
