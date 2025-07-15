/**
 * JavaScript Frontend - Système de Vote
 */

(function($) {
    'use strict';
    
    // Variables globales
    let currentCandidateId = null;
    let currentCategoryId = null;
    let isVoting = false;
    
    // Initialisation
    $(document).ready(function() {
        initVotingSystem();
    });
    
    /**
     * Initialiser le système de vote
     */
    function initVotingSystem() {
        // Initialiser les modales
        initModals();
        
        // Initialiser les événements de vote
        initVoteEvents();
        
        // Initialiser les sélecteurs de catégories
        initCategorySelectors();
        
        // Initialiser les galeries d'images
        initImageGalleries();
        
        // Initialiser les tooltips
        initTooltips();
    }
    
    /**
     * Initialiser les modales
     */
    function initModals() {
        // Fermer les modales en cliquant sur la croix
        $('.voting-modal-close').on('click', function() {
            closeModal($(this).closest('.voting-modal'));
        });
        
        // Fermer les modales en cliquant en dehors
        $('.voting-modal').on('click', function(e) {
            if (e.target === this) {
                closeModal($(this));
            }
        });
        
        // Fermer les modales avec la touche Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.voting-modal').each(function() {
                    if ($(this).is(':visible')) {
                        closeModal($(this));
                    }
                });
            }
        });
    }
    
    /**
     * Initialiser les événements de vote
     */
    function initVoteEvents() {
        // Bouton de vote sur les cartes de candidats
        $(document).on('click', '.vote-btn', function(e) {
            e.preventDefault();
            
            if (!votingSystem.isLoggedIn) {
                showMessage('error', votingSystem.strings.loginRequired);
                setTimeout(function() {
                    window.location.href = votingSystem.loginUrl;
                }, 2000);
                return;
            }
            
            const candidateId = $(this).data('candidate-id');
            const categoryId = $(this).data('category-id');
            
            showVoteConfirmation(candidateId, categoryId);
        });
        
        // Bouton de détails des candidats
        $(document).on('click', '.candidate-details-btn', function(e) {
            e.preventDefault();
            
            const candidateId = $(this).data('candidate-id');
            showCandidateDetails(candidateId);
        });
        
        // Confirmation de vote
        $('#confirm-vote').on('click', function() {
            if (currentCandidateId && currentCategoryId) {
                castVote(currentCandidateId, currentCategoryId);
            }
            closeModal($('#vote-confirmation-modal'));
        });
        
        // Annulation de vote
        $('#cancel-vote').on('click', function() {
            closeModal($('#vote-confirmation-modal'));
        });
        
        // Supprimer un vote
        $('#remove-vote-button').on('click', function() {
            if (confirm(votingSystem.strings.confirmRemoveVote)) {
                removeVote(currentCategoryId);
            }
        });
    }
    
    /**
     * Initialiser les sélecteurs de catégories
     */
    function initCategorySelectors() {
        $('#category-select').on('change', function() {
            const categoryId = $(this).val();
            if (categoryId) {
                loadCategoryResults(categoryId);
            } else {
                loadAllResults();
            }
        });
    }
    
    /**
     * Initialiser les galeries d'images
     */
    function initImageGalleries() {
        $(document).on('click', '.gallery-item img', function() {
            const src = $(this).attr('src');
            showImageModal(src);
        });
    }
    
    /**
     * Initialiser les tooltips
     */
    function initTooltips() {
        $('[data-tooltip]').each(function() {
            $(this).tooltip({
                title: $(this).data('tooltip'),
                placement: 'top',
                trigger: 'hover'
            });
        });
    }
    
    /**
     * Afficher la confirmation de vote
     */
    function showVoteConfirmation(candidateId, categoryId) {
        currentCandidateId = candidateId;
        currentCategoryId = categoryId;
        
        // Obtenir les détails du candidat
        $.ajax({
            url: votingSystem.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_candidate_details',
                candidate_id: candidateId,
                nonce: votingSystem.nonce
            },
            success: function(response) {
                if (response.success) {
                    const candidate = response.data;
                    $('#vote-confirmation-text').text(
                        `Êtes-vous sûr de vouloir voter pour "${candidate.name}" dans la catégorie "${candidate.category_name}" ?`
                    );
                    showModal($('#vote-confirmation-modal'));
                } else {
                    showMessage('error', response.data);
                }
            },
            error: function() {
                showMessage('error', votingSystem.strings.voteError);
            }
        });
    }
    
    /**
     * Enregistrer un vote
     */
    function castVote(candidateId, categoryId) {
        if (isVoting) return;
        
        isVoting = true;
        showLoading();
        
        $.ajax({
            url: votingSystem.ajaxUrl,
            type: 'POST',
            data: {
                action: 'cast_vote',
                candidate_id: candidateId,
                category_id: categoryId,
                nonce: votingSystem.nonce
            },
            success: function(response) {
                hideLoading();
                isVoting = false;
                
                if (response.success) {
                    showMessage('success', response.data.message);
                    
                    // Mettre à jour l'interface
                    updateVoteInterface(candidateId, categoryId, response.data);
                    
                    // Fermer la modale des détails si ouverte
                    closeModal($('#candidate-modal'));
                    
                    // Recharger la page après un délai
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    showMessage('error', response.data);
                }
            },
            error: function() {
                hideLoading();
                isVoting = false;
                showMessage('error', votingSystem.strings.voteError);
            }
        });
    }
    
    /**
     * Afficher les détails d'un candidat
     */
    function showCandidateDetails(candidateId) {
        showLoading();
        
        $.ajax({
            url: votingSystem.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_candidate_details',
                candidate_id: candidateId,
                nonce: votingSystem.nonce
            },
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    const candidate = response.data;
                    populateCandidateModal(candidate);
                    showModal($('#candidate-modal'));
                } else {
                    showMessage('error', response.data);
                }
            },
            error: function() {
                hideLoading();
                showMessage('error', votingSystem.strings.voteError);
            }
        });
    }
    
    /**
     * Remplir la modale des détails du candidat
     */
    function populateCandidateModal(candidate) {
        $('#candidate-photo').attr('src', candidate.photo);
        $('#candidate-name').text(candidate.name);
        $('#candidate-category').text(candidate.category_name);
        $('#candidate-votes').text(candidate.vote_count);
        $('#candidate-bio').text(candidate.biography || 'Aucune biographie disponible.');
        
        // Gérer la galerie
        const gallery = $('#candidate-gallery');
        gallery.empty();
        
        if (candidate.gallery && candidate.gallery.length > 0) {
            gallery.append('<h4>Galerie</h4>');
            const galleryGrid = $('<div class="gallery-grid"></div>');
            
            candidate.gallery.forEach(function(image) {
                galleryGrid.append(`
                    <div class="gallery-item">
                        <img src="${image}" alt="${candidate.name}">
                    </div>
                `);
            });
            
            gallery.append(galleryGrid);
        }
        
        // Gérer les boutons d'action
        const voteButton = $('#vote-button');
        const removeVoteButton = $('#remove-vote-button');
        const statusMessage = $('#vote-status-message');
        
        voteButton.hide();
        removeVoteButton.hide();
        statusMessage.removeClass('success error').text('');
        
        if (candidate.has_voted) {
            removeVoteButton.show();
            statusMessage.addClass('success').text('Vous avez déjà voté pour ce candidat.');
        } else if (candidate.can_vote) {
            voteButton.show();
            voteButton.data('candidate-id', candidate.id);
            voteButton.data('category-id', candidate.category_id);
        } else {
            statusMessage.addClass('error').text('Vous ne pouvez pas voter pour le moment.');
        }
        
        currentCategoryId = candidate.category_id;
    }
    
    /**
     * Supprimer un vote
     */
    function removeVote(categoryId) {
        $.ajax({
            url: votingSystem.ajaxUrl,
            type: 'POST',
            data: {
                action: 'remove_vote',
                category_id: categoryId,
                nonce: votingSystem.nonce
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.data);
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    showMessage('error', response.data);
                }
            },
            error: function() {
                showMessage('error', votingSystem.strings.voteError);
            }
        });
    }
    
    /**
     * Charger les résultats d'une catégorie
     */
    function loadCategoryResults(categoryId) {
        const resultsContent = $('#results-content');
        resultsContent.html('<div class="voting-loading">Chargement des résultats...</div>');
        
        $.ajax({
            url: votingSystem.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_category_results',
                category_id: categoryId,
                nonce: votingSystem.nonce
            },
            success: function(response) {
                if (response.success) {
                    resultsContent.html(response.data);
                } else {
                    resultsContent.html('<p class="voting-message error">Erreur lors du chargement des résultats.</p>');
                }
            },
            error: function() {
                resultsContent.html('<p class="voting-message error">Erreur lors du chargement des résultats.</p>');
            }
        });
    }
    
    /**
     * Charger tous les résultats
     */
    function loadAllResults() {
        const resultsContent = $('#results-content');
        resultsContent.html('<div class="voting-loading">Chargement des résultats...</div>');
        
        $.ajax({
            url: votingSystem.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_all_results',
                nonce: votingSystem.nonce
            },
            success: function(response) {
                if (response.success) {
                    resultsContent.html(response.data);
                } else {
                    resultsContent.html('<p class="voting-message error">Erreur lors du chargement des résultats.</p>');
                }
            },
            error: function() {
                resultsContent.html('<p class="voting-message error">Erreur lors du chargement des résultats.</p>');
            }
        });
    }
    
    /**
     * Mettre à jour l'interface après un vote
     */
    function updateVoteInterface(candidateId, categoryId, data) {
        // Mettre à jour le compteur de votes
        $(`.voting-candidate-card[data-candidate-id="${candidateId}"] .candidate-votes`).text(
            `${data.vote_count} vote${data.vote_count > 1 ? 's' : ''}`
        );
        
        // Ajouter le badge de vote
        $(`.voting-candidate-card[data-candidate-id="${candidateId}"] .candidate-image`).append(`
            <div class="voted-badge">
                <i class="fas fa-check"></i>
            </div>
        `);
        
        // Désactiver tous les boutons de vote de la catégorie
        $(`.vote-btn[data-category-id="${categoryId}"]`).prop('disabled', true).text('Voté');
        
        // Mettre à jour le statut de vote
        $('.voted-status').text(`Vous avez voté pour : ${data.candidate_name}`);
    }
    
    /**
     * Afficher une modale
     */
    function showModal(modal) {
        modal.fadeIn(300);
        $('body').addClass('modal-open');
    }
    
    /**
     * Fermer une modale
     */
    function closeModal(modal) {
        modal.fadeOut(300);
        $('body').removeClass('modal-open');
    }
    
    /**
     * Afficher un message
     */
    function showMessage(type, message) {
        const messageHtml = `
            <div class="voting-message ${type}">
                ${message}
                <button type="button" class="close-message">&times;</button>
            </div>
        `;
        
        // Supprimer les anciens messages
        $('.voting-message').remove();
        
        // Ajouter le nouveau message
        $('.voting-system').prepend(messageHtml);
        
        // Auto-fermeture après 5 secondes
        setTimeout(function() {
            $('.voting-message').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
        
        // Fermer manuellement
        $(document).on('click', '.close-message', function() {
            $(this).closest('.voting-message').fadeOut(300, function() {
                $(this).remove();
            });
        });
    }
    
    /**
     * Afficher le chargement
     */
    function showLoading() {
        $('body').append('<div id="voting-loading-overlay"><div class="voting-loading">Chargement...</div></div>');
    }
    
    /**
     * Masquer le chargement
     */
    function hideLoading() {
        $('#voting-loading-overlay').fadeOut(300, function() {
            $(this).remove();
        });
    }
    
    /**
     * Afficher une modale d'image
     */
    function showImageModal(src) {
        const imageModal = $(`
            <div class="voting-modal" id="image-modal">
                <div class="voting-modal-content" style="max-width: 90%; max-height: 90%;">
                    <span class="voting-modal-close">&times;</span>
                    <img src="${src}" style="width: 100%; height: auto; border-radius: 8px;">
                </div>
            </div>
        `);
        
        $('body').append(imageModal);
        showModal(imageModal);
        
        // Fermer la modale d'image
        imageModal.find('.voting-modal-close').on('click', function() {
            closeModal(imageModal);
            setTimeout(function() {
                imageModal.remove();
            }, 300);
        });
        
        imageModal.on('click', function(e) {
            if (e.target === this) {
                closeModal(imageModal);
                setTimeout(function() {
                    imageModal.remove();
                }, 300);
            }
        });
    }
    
    /**
     * Valider un formulaire
     */
    function validateForm(form) {
        let isValid = true;
        const errors = [];
        
        form.find('[required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                errors.push($(this).attr('name') + ' est requis');
                $(this).addClass('error');
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (!isValid) {
            showMessage('error', 'Veuillez corriger les erreurs suivantes : ' + errors.join(', '));
        }
        
        return isValid;
    }
    
    /**
     * Formater un nombre
     */
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }
    
    /**
     * Débouncer une fonction
     */
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
    /**
     * Throttler une fonction
     */
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    // Exposer les fonctions publiques
    window.VotingSystem = {
        showMessage: showMessage,
        showModal: showModal,
        closeModal: closeModal,
        castVote: castVote,
        removeVote: removeVote,
        showCandidateDetails: showCandidateDetails,
        validateForm: validateForm,
        formatNumber: formatNumber
    };
    
})(jQuery); 