<?php include 'includes/header.php'; ?>

    <section class="feedback-section">
        <div class="container">
            <div class="form-container">
                <h2>¿Qué opinas de nuestra plataforma?</h2>
                <p style="text-align: center; margin-bottom: 25px; color: #555;">¡Queremos escucharte! Tu feedback nos ayuda a mejorar.</p>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label>Califica nuestra plataforma:</label>
                        <div class="rating-stars">
                            <span class="star" data-value="1"><i class="fas fa-star"></i></span>
                            <span class="star" data-value="2"><i class="fas fa-star"></i></span>
                            <span class="star" data-value="3"><i class="fas fa-star"></i></span>
                            <span class="star" data-value="4"><i class="fas fa-star"></i></span>
                            <span class="star" data-value="5"><i class="fas fa-star"></i></span>
                        </div>
                        <input type="hidden" name="puntuacion_general" id="hidden_rating" value="0">
                    </div>
                    <div class="form-group">
                        <label for="comentarios">Cuéntanos tus comentarios o sugerencias:</label>
                        <textarea id="comentarios" name="comentarios" rows="6" placeholder="Escribe aquí tus comentarios..."></textarea>
                    </div>
                     <div class="form-group">
                        <label for="funcionalidad_util">¿Qué funcionalidad te ha sido más útil?</label>
                        <input type="text" id="funcionalidad_util" name="funcionalidad_util" placeholder="Ej: Denuncia Anónima, Citas con psicólogos">
                    </div>
                    <div class="form-group">
                        <label for="sugerencias_mejora">¿Qué podríamos mejorar?</label>
                        <textarea id="sugerencias_mejora" name="sugerencias_mejora" rows="4" placeholder="Ej: Añadir más horarios, mejorar la búsqueda."></textarea>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="anonimo_feedback" name="anonimo_feedback" checked>
                        <label for="anonimo_feedback">Enviar feedback de forma anónima</label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Enviar Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>