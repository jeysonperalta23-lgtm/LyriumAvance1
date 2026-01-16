// bioforo.js - VERSIÓN LIMPIA SIN DUPLICACIONES
console.log('BioForo cargado correctamente');

// Sistema de identidad anónima persistente
if (!localStorage.getItem('anonimo_id')) {
    const anonimoId = 'anon_' + Math.random().toString(36).substr(2, 9);
    localStorage.setItem('anonimo_id', anonimoId);
    console.log('Nuevo anónimo_id generado:', anonimoId);
}

// Funciones globales
window.toggleFiltroDropdown = function() {
    const dropdown = document.getElementById('filtroDropdown');
    dropdown.classList.toggle('show');
};

window.closeCreateModal = function() {
    const modal = document.getElementById('createModal');
    modal.classList.remove('modal-showing');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 300);
};

window.openCreateModal = function() {
    const modal = document. getElementById('createModal');
    modal.classList.remove('hidden');
    modal.offsetHeight;
    modal.classList.add('modal-showing');
    document.body.classList.add('overflow-hidden');

    const tituloInput = document.getElementById('titulo');
    const contenidoInput = document.getElementById('contenido');
    const tituloCount = document.getElementById('titulo-count');
    const contenidoCount = document.getElementById('contenido-count');

    if (tituloInput && tituloCount) {
        tituloInput.addEventListener('input', function() {
            tituloCount.textContent = this.value.length + '/120';
        });
    }

    if (contenidoInput && contenidoCount) {
        contenidoInput.addEventListener('input', function() {
            contenidoCount.textContent = this. value.length + '/2000';
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    setTimeout(() => {
        if (tituloInput) tituloInput.focus();
    }, 200);
};

// Cerrar menús al hacer clic fuera
document.addEventListener('click', function(e) {
    const filtroBtn = document.querySelector('[onclick="toggleFiltroDropdown()"]');
    const filtroMenu = document.getElementById('filtroDropdown');
    if (filtroBtn && filtroMenu && !filtroBtn.contains(e.target) && !filtroMenu.contains(e.target)) {
        filtroMenu.classList.remove('show');
    }

    document.querySelectorAll('.reaction-popup').forEach(popup => {
        if (! popup.contains(e.target) && !e.target.closest('.reaction-btn-tema')) {
            popup.classList. remove('show');
        }
    });
});

// Función para cargar reacciones del usuario actual
window.loadUserReactions = function() {
    const temaIds = Array.from(document.querySelectorAll('[id^="tema-"]')).map(el => 
        el.id.replace('tema-', '')
    ).filter(id => ! isNaN(id));
    
    const respuestaIds = Array.from(document.querySelectorAll('[id^="respuesta-"]')).map(el => 
        el.id. replace('respuesta-', '')
    ).filter(id => !isNaN(id));
    
    if (temaIds.length === 0 && respuestaIds.length === 0) return;
    
    $. ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            action: 'get_user_reactions',
            tema_ids: temaIds,
            respuesta_ids: respuestaIds,
            anonimo_id: localStorage.getItem('anonimo_id')
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Object.keys(response.reactions).forEach(key => {
                    const [type, id] = key.split('_');
                    
                    if (type === 'tema') {
                        const reaction = response.reactions[key];
                        const btn = document.getElementById(`reaction-btn-${id}`);
                        const icon = document.getElementById(`reaction-icon-${id}`);
                        const label = document.getElementById(`reaction-label-${id}`);
                        
                        if (btn && icon && label) {
                            const iconMap = {
                                'like':  '👍', 'love': '❤️', 'haha': '😂',
                                'wow': '😮', 'sad': '😢', 'angry': '😡'
                            };
                            const labelMap = {
                                'like': 'Me gusta', 'love': 'Me encanta', 'haha': 'Me divierte',
                                'wow':  'Me asombra', 'sad': 'Me entristece', 'angry': 'Me enoja'
                            };
                            
                            icon.textContent = iconMap[reaction] || '<i class="ph-heart"></i>';
                            label. textContent = labelMap[reaction] || 'Reaccionar';
                            btn.classList.add(`reaction-${reaction}`);
                            btn.dataset.reaction = reaction;
                        }
                    } else if (type === 'respuesta') {
                        const reaction = response.reactions[key];
                        const likeBtn = document.getElementById(`like-btn-${id}`);
                        const angryBtn = document.getElementById(`angry-btn-${id}`);
                        
                        if (reaction === 'like' && likeBtn) {
                            likeBtn.classList.add('active-like');
                            likeBtn.querySelector('i').className = 'ph-thumbs-up-fill';
                        } else if (reaction === 'angry' && angryBtn) {
                            angryBtn.classList. add('active-angry');
                            angryBtn.querySelector('i').className = 'ph-thumbs-down-fill';
                        }
                    }
                });
            }
        }
    });
};

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando BioForo...');

    loadUserReactions();

    const createModal = document.getElementById('createModal');
    if (createModal) {
        createModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });
    }

    document.querySelectorAll('textarea[id^="respuesta-input-"]').forEach(textarea => {
        textarea.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                const temaId = this.id.replace('respuesta-input-', '');
                submitComment(temaId);
            }
        });
    });

    const formNuevoTema = document.getElementById('formNuevoTema');
    if (formNuevoTema) {
        formNuevoTema.onsubmit = function(e) {
            e.preventDefault();

            const titulo = document.getElementById('titulo').value. trim();
            const contenido = document.getElementById('contenido').value.trim();
            const categoria = document.getElementById('categoria').value;

            if (! titulo || !contenido || !categoria) {
                showNotification('Completa todos los campos', 'error');
                return false;
            }

            if (titulo.length < 5) {
                showNotification('El título debe tener al menos 5 caracteres', 'error');
                return false;
            }

            if (contenido.length < 10) {
                showNotification('El contenido debe tener al menos 10 caracteres', 'error');
                return false;
            }

            document.getElementById('submitText').classList.add('hidden');
            document.getElementById('submitLoading').classList.remove('hidden');
            document.getElementById('submitNuevoTema').disabled = true;

            $. ajax({
                url: window. location.href,
                method: 'POST',
                data:  {
                    action: 'nuevo_tema_ajax',
                    titulo:  titulo,
                    contenido:  contenido,
                    categoria:  categoria,
                    anonimo_id: localStorage.getItem('anonimo_id')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        closeCreateModal();
                        formNuevoTema.reset();
                        document.getElementById('titulo-count').textContent = '0/120';
                        document.getElementById('contenido-count').textContent = '0/2000';
                        document.getElementById('contenido').style.height = 'auto';

                        showNotification('¡Tema creado exitosamente!', 'success');

                        setTimeout(() => {
                            window.location.href = response.redirect || window.location.href;
                        }, 1000);
                    } else {
                        showNotification(response.error || 'Error al crear tema', 'error');
                    }
                },
                error:  function() {
                    showNotification('Error de conexión', 'error');
                },
                complete: function() {
                    document.getElementById('submitText').classList.remove('hidden');
                    document.getElementById('submitLoading').classList.add('hidden');
                    document.getElementById('submitNuevoTema').disabled = false;
                }
            });

            return false;
        };
    }

    console.log('BioForo inicializado correctamente');
});

// Función de notificación
window.showNotification = function(message, type = 'info') {
    const colors = {
        'success': 'bg-emerald-500',
        'error': 'bg-red-500',
        'info': 'bg-blue-500'
    };

    const icon = {
        'success': 'ph-check-circle',
        'error': 'ph-warning-circle',
        'info':  'ph-info'
    };

    document.querySelectorAll('.notification-temp').forEach(el => el.remove());

    const notification = document.createElement('div');
    notification.className = `notification-temp fixed bottom-4 right-4 ${colors[type]} text-white px-4 py-2.5 rounded-lg shadow-lg z-50 flex items-center gap-2 text-sm`;
    notification.innerHTML = `
        <i class="${icon[type]}"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
};

// Funciones para reacciones en temas
window.toggleReactionPopup = function(temaId) {
    const popup = document.getElementById(`reactions-${temaId}`);
    popup.classList.toggle('show');
};

window.addReactionTema = function(temaId, tipo) {
    document.getElementById(`reactions-${temaId}`).classList.remove('show');
    
    const iconMap = {
        'like':  '👍', 'love':  '❤️', 'haha': '😂',
        'wow': '😮', 'sad': '😢', 'angry': '😡'
    };
    const labelMap = {
        'like': 'Me gusta', 'love': 'Me encanta', 'haha': 'Me divierte',
        'wow': 'Me asombra', 'sad': 'Me entristece', 'angry': 'Me enoja'
    };

    const btn = document.getElementById(`reaction-btn-${temaId}`);
    const reactionIcon = document.getElementById(`reaction-icon-${temaId}`);
    const reactionLabel = document.getElementById(`reaction-label-${temaId}`);
    const totalCounter = document.getElementById(`total-reactions-${temaId}`);

    const currentReaction = btn.dataset.reaction;
    const isRemoving = currentReaction === tipo;

    let currentCount = parseInt(totalCounter.textContent) || 0;
    
    if (isRemoving) {
        reactionIcon.innerHTML = '<i class="ph-heart"></i>';
        reactionLabel. textContent = 'Reaccionar';
        btn.classList.remove('reaction-like', 'reaction-love', 'reaction-haha', 'reaction-wow', 'reaction-sad', 'reaction-angry');
        delete btn.dataset.reaction;
        totalCounter.textContent = Math.max(0, currentCount - 1);
    } else {
        if (! currentReaction) {
            totalCounter.textContent = currentCount + 1;
        }
        
        btn.classList.remove('reaction-like', 'reaction-love', 'reaction-haha', 'reaction-wow', 'reaction-sad', 'reaction-angry');
        btn.classList.add(`reaction-${tipo}`);
        btn.dataset.reaction = tipo;
        reactionIcon.textContent = iconMap[tipo];
        reactionLabel.textContent = labelMap[tipo];
    }

    $. ajax({
        url: window. location.href,
        method: 'POST',
        data:  {
            action: 'toggle_reaccion_tema',
            tema_id: temaId,
            tipo: tipo,
            anonimo_id: localStorage.getItem('anonimo_id')
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                totalCounter.textContent = response.counts.total_reacciones;
                
                if (response.action === 'inserted') {
                    showNotification('Reacción añadida', 'success');
                } else if (response.action === 'deleted') {
                    showNotification('Reacción removida', 'info');
                }
            } else {
                showNotification(response.error || 'Error al reaccionar', 'error');
                loadUserReactions();
            }
        },
        error: function() {
            showNotification('Error de conexión', 'error');
            loadUserReactions();
        }
    });
};

// Funciones para reacciones en respuestas
window.toggleReactionRespuesta = function(respuestaId, tipo) {
    const likeBtn = document.getElementById(`like-btn-${respuestaId}`);
    const angryBtn = document.getElementById(`angry-btn-${respuestaId}`);
    const likeCount = document.querySelector(`.resp-likes-${respuestaId}`);
    const angryCount = document.querySelector(`.resp-angry-${respuestaId}`);

    const currentLikeActive = likeBtn.classList.contains('active-like');
    const currentAngryActive = angryBtn.classList.contains('active-angry');

    if (tipo === 'like') {
        if (currentLikeActive) {
            likeBtn.classList.remove('active-like');
            likeBtn.querySelector('i').className = 'ph-thumbs-up';
            likeCount.textContent = Math.max(0, parseInt(likeCount.textContent) - 1);
        } else {
            likeBtn.classList.add('active-like');
            likeBtn.querySelector('i').className = 'ph-thumbs-up-fill';
            likeCount.textContent = parseInt(likeCount.textContent) + 1;
            
            if (currentAngryActive) {
                angryBtn.classList.remove('active-angry');
                angryBtn.querySelector('i').className = 'ph-thumbs-down';
                angryCount.textContent = Math.max(0, parseInt(angryCount.textContent) - 1);
            }
        }
    } else if (tipo === 'angry') {
        if (currentAngryActive) {
            angryBtn. classList.remove('active-angry');
            angryBtn.querySelector('i').className = 'ph-thumbs-down';
            angryCount.textContent = Math. max(0, parseInt(angryCount.textContent) - 1);
        } else {
            angryBtn.classList.add('active-angry');
            angryBtn.querySelector('i').className = 'ph-thumbs-down-fill';
            angryCount.textContent = parseInt(angryCount.textContent) + 1;
            
            if (currentLikeActive) {
                likeBtn.classList.remove('active-like');
                likeBtn.querySelector('i').className = 'ph-thumbs-up';
                likeCount.textContent = Math.max(0, parseInt(likeCount.textContent) - 1);
            }
        }
    }

    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            action: 'toggle_reaccion_respuesta',
            respuesta_id: respuestaId,
            tipo: tipo,
            anonimo_id: localStorage.getItem('anonimo_id')
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                likeCount.textContent = response.counts. likes_count;
                angryCount. textContent = response.counts.angry_count;

                if (response.user_reaction === 'like') {
                    likeBtn.classList.add('active-like');
                    angryBtn.classList.remove('active-angry');
                    likeBtn.querySelector('i').className = 'ph-thumbs-up-fill';
                    angryBtn.querySelector('i').className = 'ph-thumbs-down';
                } else if (response.user_reaction === 'angry') {
                    likeBtn.classList.remove('active-like');
                    angryBtn.classList.add('active-angry');
                    likeBtn.querySelector('i').className = 'ph-thumbs-up';
                    angryBtn. querySelector('i').className = 'ph-thumbs-down-fill';
                } else {
                    likeBtn.classList.remove('active-like');
                    angryBtn. classList.remove('active-angry');
                    likeBtn.querySelector('i').className = 'ph-thumbs-up';
                    angryBtn.querySelector('i').className = 'ph-thumbs-down';
                }

                if (response.action === 'inserted') {
                    showNotification('Reacción añadida', 'success');
                } else if (response.action === 'deleted') {
                    showNotification('Reacción removida', 'info');
                }
            } else {
                showNotification(response.error || 'Error al reaccionar', 'error');
                loadUserReactions();
            }
        },
        error: function() {
            showNotification('Error de conexión', 'error');
            loadUserReactions();
        }
    });
};

// Funciones para respuestas
window.citarRespuesta = function(temaId, respuestaId, autor, contenido) {
    const quoteContainer = document.getElementById(`quote-container-${temaId}`);
    const citaIdInput = document.getElementById(`cita-id-${temaId}`);

    citaIdInput.value = respuestaId;

    quoteContainer.innerHTML = `
        <div class="quote-box-whatsapp">
            <div class="quote-author-whatsapp">${autor}</div>
            <div class="quote-text-whatsapp">${contenido}</div>
        </div>
    `;

    const replyForm = document.getElementById(`reply-form-${temaId}`);
    if (replyForm. classList.contains('hidden')) {
        replyForm. classList.remove('hidden');
    }

    document.getElementById(`respuesta-input-${temaId}`).focus();
};

window.toggleCommentField = function(temaId) {
    const replyForm = document.getElementById(`reply-form-${temaId}`);
    replyForm.classList.toggle('hidden');

    if (replyForm.classList.contains('hidden')) {
        document.getElementById(`quote-container-${temaId}`).innerHTML = '';
        document. getElementById(`cita-id-${temaId}`).value = '';
    } else {
        setTimeout(() => {
            document.getElementById(`respuesta-input-${temaId}`).focus();
        }, 100);
    }
};

window.cerrarFormularioRespuesta = function(temaId) {
    const replyForm = document.getElementById(`reply-form-${temaId}`);
    replyForm.classList.add('hidden');
    document.getElementById(`quote-container-${temaId}`).innerHTML = '';
    document. getElementById(`cita-id-${temaId}`).value = '';
};

window.submitComment = function(temaId) {
    const textarea = document.getElementById(`respuesta-input-${temaId}`);
    const contenido = textarea.value.trim();
    const citaId = document.getElementById(`cita-id-${temaId}`).value;

    if (!contenido) {
        showNotification('Escribe una respuesta', 'error');
        textarea.focus();
        return false;
    }

    const submitBtn = document.getElementById(`submit-btn-${temaId}`);
    const submitText = document.getElementById(`submit-text-${temaId}`);
    const submitLoading = document. getElementById(`submit-loading-${temaId}`);
    
    submitText.classList.add('hidden');
    submitLoading.classList.remove('hidden');
    submitBtn.disabled = true;

    $.ajax({
        url: window.location. href,
        method: 'POST',
        data: {
            action: 'responder_ajax',
            tema_id: temaId,
            contenido: contenido,
            cita_id: citaId || '',
            anonimo_id:  localStorage.getItem('anonimo_id')
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('Respuesta publicada', 'success');
                
                setTimeout(() => {
                    eliminarTodosDuplicados();
                    location.reload();
                }, 1000);
            } else {
                showNotification(response.error || 'Error al publicar respuesta', 'error');
            }
        },
        error: function() {
            showNotification('Error de conexión', 'error');
        },
        complete: function() {
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
            submitBtn.disabled = false;
        }
    });

    return false;
};

window.verMasRespuestas = function(temaId) {
    const offset = document.querySelectorAll(`#respuestas-list-${temaId} .respuesta-item`).length;
    const verMasBtn = document. getElementById(`ver-mas-btn-${temaId}`);
    const verMenosBtn = document.getElementById(`ver-menos-btn-${temaId}`);
    const respuestasList = document.getElementById(`respuestas-list-${temaId}`);

    // ✅ Validar que existan los elementos
    if (!verMasBtn || !respuestasList) {
        console.error(`Elementos no encontrados para tema ${temaId}`);
        return;
    }

    const originalText = verMasBtn. innerHTML;
    verMasBtn.innerHTML = '<span class="loader-small"></span> Cargando...';
    verMasBtn.disabled = true;  // ✅ Deshabilitar botón

    $. ajax({
        url: window. location.href,
        method: 'POST',
        data:  {
            action: 'cargar_mas_respuestas',
            tema_id: temaId,
            offset: offset,
            anonimo_id:  localStorage.getItem('anonimo_id')
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // 1. Insertar HTML
                respuestasList.insertAdjacentHTML('beforeend', response.html);
                respuestasList.classList.add('expanded');
                
                // 2. ✅ Eliminar duplicados
                setTimeout(() => {
                    eliminarDuplicadosRespuestas();
                }, 100);
                
                // 3.  Cargar reacciones
                setTimeout(() => {
                    loadUserReactions();
                }, 150);
                
                // 4. Actualizar botones
                verMasBtn.classList.add('hidden');
                if (verMenosBtn) {
                    verMenosBtn.classList.remove('hidden');
                }
                
                showNotification('Respuestas cargadas correctamente', 'success');
            } else {
                showNotification(response.error || 'Error al cargar respuestas', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX cargar_mas_respuestas:', error);
            console.error('Response:', xhr.responseText);
            showNotification('Error de conexión', 'error');
        },
        complete: function() {
            verMasBtn.innerHTML = originalText;
            verMasBtn.disabled = false;  // ✅ Re-habilitar botón
        }
    });
};

window.verMenosRespuestas = function(temaId) {
    const respuestasList = document.getElementById(`respuestas-list-${temaId}`);
    const verMasBtn = document.getElementById(`ver-mas-btn-${temaId}`);
    const verMenosBtn = document.getElementById(`ver-menos-btn-${temaId}`);

    // ✅ Validar que existan los elementos
    if (! respuestasList || !verMasBtn) {
        console.error(`Elementos no encontrados para tema ${temaId}`);
        return;
    }

    respuestasList.classList.remove('expanded');
    
    // Eliminar respuestas extras (mantener solo TOP 3)
    const todasLasRespuestas = respuestasList.querySelectorAll('.respuesta-item');
    todasLasRespuestas.forEach((respuesta, index) => {
        if (index >= 3) {
            respuesta.style.opacity = '0';
            respuesta.style.transform = 'translateY(-10px)';
            setTimeout(() => respuesta.remove(), 200);
        }
    });

    // Actualizar botones
    setTimeout(() => {
        verMasBtn.classList.remove('hidden');
        if (verMenosBtn) {
            verMenosBtn. classList.add('hidden');
        }
        
        // ✅ Eliminar duplicados por si acaso
        eliminarDuplicadosRespuestas();
    }, 250);
};

window.verMenosRespuestas = function(temaId) {
    const respuestasList = document.getElementById(`respuestas-list-${temaId}`);
    const verMasBtn = document.getElementById(`ver-mas-btn-${temaId}`);
    const verMenosBtn = document. getElementById(`ver-menos-btn-${temaId}`);

    respuestasList.classList.remove('expanded');
    const todasLasRespuestas = respuestasList.querySelectorAll('.respuesta-item');
    todasLasRespuestas.forEach((respuesta, index) => {
        if (index >= 3) {
            respuesta.remove();
        }
    });

    verMasBtn.classList. remove('hidden');
    verMenosBtn.classList.add('hidden');
};

window.compartirTema = function(temaId) {
    const url = window.location.origin + window.location.pathname + '?tema=' + temaId + '#tema-' + temaId;
    if (navigator.share) {
        navigator. share({
            title: document.querySelector(`#tema-${temaId} h3`).textContent,
            text: 'Mira este tema en BioForo',
            url:  url
        });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Enlace copiado al portapapeles', 'success');
        });
    }
};

// Ver TODOS los temas restantes (estático)
window.verMasTemas = function() {
    const btn = document.getElementById('ver-mas-temas-btn');
    const textSpan = document.getElementById('ver-mas-temas-text');
    const loadingSpan = document.getElementById('ver-mas-temas-loading');
    const verMenosBtn = document.getElementById('ver-menos-temas-btn');
    const contadorCargados = document.getElementById('temas-cargados-count');
    
    // Guardar texto original del botón
    const textoOriginal = textSpan.textContent;
    
    textSpan.classList.add('hidden');
    loadingSpan.classList.remove('hidden');
    btn.disabled = true;

    const temasYaCargados = Array.from(document.querySelectorAll('[id^="tema-"]'))
        .map(el => el.id.replace('tema-', ''))
        .filter(id => ! isNaN(id));

    const urlParams = new URLSearchParams(window. location.search);
    const categoria = urlParams.get('categoria') || 0;

    $. ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            action: 'cargar_mas_temas',
            temas_cargados: temasYaCargados,
            categoria: categoria,
            anonimo_id: localStorage.getItem('anonimo_id')
        },
        dataType: 'json',
        success: function(response) {
            if (response. success) {
                btn.parentElement.insertAdjacentHTML('beforebegin', response.html);

                setTimeout(() => {
            eliminarTodosDuplicados();
        }, 100);
                
                const nuevoTotal = temasYaCargados.length + response.count;
                if (contadorCargados) {
                    contadorCargados.textContent = nuevoTotal;
                }
                
                loadUserReactions();
                
                btn.classList.add('hidden');
                if (verMenosBtn) {
                    verMenosBtn.classList.remove('hidden');
                }
                
                const mensaje = categoria > 0 
                    ? `${response.count} temas más de esta categoría cargados`
                    : `${response.count} temas más cargados`;
                showNotification(mensaje, 'success');
            } else {
                showNotification(response.error || 'Error al cargar temas', 'error');
            }
        },
        error: function() {
            showNotification('Error de conexión', 'error');
        },
        complete: function() {
            textSpan.classList.remove('hidden');
            loadingSpan.classList.add('hidden');
            btn.disabled = false;
        }
    });
};

// Volver a mostrar solo temas prioritarios (dinámico con scroll)
window.verMenosTemas = function() {
    const verMasBtn = document.getElementById('ver-mas-temas-btn');
    const verMenosBtn = document.getElementById('ver-menos-temas-btn');
    const contadorCargados = document.getElementById('temas-cargados-count');
    
    // Eliminar todos los temas que se cargaron después
    const temasExtras = document.querySelectorAll('[data-tema-extra="true"]');
    const cantidadEliminada = temasExtras.length;
    
    temasExtras.forEach(tema => {
        tema.style.opacity = '0';
        tema.style.transform = 'translateY(20px)';
        setTimeout(() => tema.remove(), 300);
    });
    
    // Actualizar contador
    setTimeout(() => {

        eliminarTodosDuplicados();

        const temasRestantes = document.querySelectorAll('[id^="tema-"]').length;
        if (contadorCargados) {
            contadorCargados.textContent = temasRestantes;
        }
        
        // Mostrar "Ver más", ocultar "Ver menos"
        if (verMasBtn) {
            verMasBtn.classList.remove('hidden');
        }
        if (verMenosBtn) {
            verMenosBtn.classList. add('hidden');
        }
        
        // Scroll suave hacia arriba (al primer tema)
        const primerTema = document.querySelector('[id^="tema-"]');
        if (primerTema) {
            primerTema.scrollIntoView({ behavior: 'smooth', block:  'start' });
        }
        
        showNotification(`${cantidadEliminada} temas ocultados`, 'info');
    }, 350);
};

// ==========================================
// SISTEMA ANTI-DUPLICADOS COMPLETO
// ==========================================
// Función para eliminar duplicados de respuestas
function eliminarDuplicadosRespuestas() {
    const respuestasVistas = new Set();
    let duplicadosEliminados = 0;
    
    document.querySelectorAll('[id^="respuesta-"]').forEach(respuesta => {
        const respuestaId = respuesta.id;
        
        if (respuestasVistas.has(respuestaId)) {
            console.warn('🗑️ Eliminando respuesta duplicada:', respuestaId);
            respuesta.remove();
            duplicadosEliminados++;
        } else {
            respuestasVistas. add(respuestaId);
        }
    });
    
    if (duplicadosEliminados > 0) {
        console.log(`✅ Se eliminaron ${duplicadosEliminados} respuestas duplicadas`);
    }
    
    return duplicadosEliminados;
}

// Función para eliminar TODOS los duplicados
function eliminarTodosDuplicados() {
    const respuestasDuplicadas = eliminarDuplicadosRespuestas();
    
    return respuestasDuplicadas;
}

// Exponer función globalmente para usar después de AJAX
window.eliminarDuplicados = eliminarTodosDuplicados;