-- ========================================
-- SISTEMA DE BLOQUES DINÁMICOS PARA TIENDAS
-- Permite reorganizar bloques visualmente
-- ========================================

-- Agregar columna para configuración de bloques
ALTER TABLE tiendas 
ADD COLUMN bloques_config TEXT DEFAULT NULL 
COMMENT 'JSON con configuración de bloques dinámicos';

-- Estructura JSON esperada:
-- {
--   "bloques": [
--     {
--       "id": "banner-principal",
--       "tipo": "banner",
--       "orden": 1,
--       "visible": true
--     },
--     {
--       "id": "productos-scroll",
--       "tipo": "productos-scroll",
--       "orden": 2,
--       "visible": true
--     },
--     {
--       "id": "grid-productos",
--       "tipo": "productos-grid",
--       "orden": 3,
--       "visible": true
--     }
--   ]
-- }
