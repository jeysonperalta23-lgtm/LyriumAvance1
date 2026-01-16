// filtro_contenido.js - Sistema Anti-Lisuras y Contenido Ofensivo v2.0
console.log('Sistema de filtrado de contenido cargado');

// ==========================================
// LISTA DE PALABRAS PROHIBIDAS (ESPAÑOL)
// ==========================================
const palabrasProhibidas = [
    // ===== LISURAS COMUNES =====
    'puta', 'puto', 'putx', 'mierda', 'cagada', 'carajo', 'coño', 'cojudo', 'huevón', 
    'huevon', 'webon', 'weon', 'cabron', 'cabrón', 'pendejo', 'gilipollas', 'idiota',
    'imbecil', 'imbécil', 'estúpido', 'estupido', 'tarado', 'retrasado', 'subnormal',
    
    // ===== INSULTOS SEXUALES =====
    'zorra', 'perra', 'marica', 'maricón', 'maricon', 'joto', 'culero', 'culera',
    'verga', 'pinga', 'pija', 'chingar', 'joder', 'follar', 'coger',
    
    // ===== RACISMO Y DISCRIMINACIÓN =====
    'negro de mierda', 'cholo', 'indio', 'serrano', 'mono', 'simio', 'nazi', 'facho',
    'sudaca', 'mojado', 'ilegal', 'alien', 'rata', 'cucaracha',
    
    // ===== INSULTOS RELIGIOSOS =====
    'hijo de puta', 'hijueputa', 'hijo de perra', 'concha tu madre', 'ctm', 'hdp',
    'conchesumadre', 'reconcha', 'malparido', 'gonorrea',
    
    // ===== VARIACIONES CREATIVAS =====
    'mrda', 'mrd', 'ptm', 'ptmr', 'csm', 'csmare', 'hp', 'cdtm',
    'pndj', 'pndjo', 'cjd', 'wbn', 'wvn', 'hevon', 'hvn',
    
    // ===== INSULTOS GENÉRICOS AGRESIVOS =====
    'basura', 'escoria', 'lacra', 'sabandija', 'alimaña', 'desgraciado',
    'miserable', 'infeliz', 'parásito', 'rata inmunda',
    
    // ===== CONTENIDO SEXUAL EXPLÍCITO - ACTOS =====
    'mamada', 'chupada', 'pete', 'paja', 'masturbación', 'masturbar',
    'sexo oral', 'felación', 'felacion', 'cunilingus', 'cunnilingus',
    'penetración', 'penetracion', 'cogida', 'follada', 'chingada',
    'violación', 'violacion', 'violar', 'forzar', 'abusar sexualmente',
    'sexo anal', 'anal', 'sodomía', 'sodomia', 'gang bang', 'gangbang',
    'orgía', 'orgia', 'trío', 'trio sexual', 'intercambio de parejas',
    'bukkake', 'creampie', 'squirt', 'eyacular', 'eyaculación', 'corrida',
    'venirse', 'acabar', 'venida', 'orgasmo', 'clímax sexual',
    
    // ===== PARTES DEL CUERPO CONTEXTO SEXUAL =====
    'pene', 'pito', 'pipi', 'miembro', 'falo', 'poronga', 'chota', 'bicho',
    'vagina', 'concha', 'chocho', 'coño', 'chucha', 'papaya', 'pepa',
    'tetas', 'pechos', 'senos', 'chichis', 'bubis', 'pezones', 'tetamen',
    'culo', 'nalgas', 'trasero', 'pompis', 'cola', 'ano', 'ojete',
    'testículos', 'huevos', 'bolas', 'cojones', 'pelotas',
    'polla', 'rabo', 'tranca', 'riata', 'machete', 'manguera',
    'clítoris', 'clitoris', 'vulva', 'labios vaginales',
    
    // ===== JERGA SEXUAL LATINOAMERICANA =====
    'culear', 'tirar', 'templar', 'pisar', 'mojar', 'clavar',
    'empalmar', 'ensartar', 'meter', 'darle', 'hacerlo', 'singando',
    'chingar', 'garchar', 'cachar', 'culiar', 'pichar',
    'mamar', 'chupar', 'soplar', 'lamer', 'tragar',
    'pajear', 'pajearse', 'hacerse la paja', 'jalársela', 'cascársela',
    'dedear', 'meter el dedo', 'tocar', 'sobar', 'manosear',
    
    // ===== CONTEXTO PORNOGRÁFICO =====
    'porno', 'pornografía', 'pornografia', 'xxx', 'triple x',
    'hentai', 'doujin', 'rule 34', 'onlyfans', 'webcam sex',
    'cam girl', 'camgirl', 'prostituta', 'puta barata', 'ramera',
    'escort', 'prostitución', 'burdel', 'putero', 'streaptease',
    'stripper', 'table dance', 'sexo en vivo', 'show erótico',
    
    // ===== FETICHES Y PARAFILIAS =====
    'bdsm', 'bondage', 'sadomasoquismo', 'dominación', 'sumisión',
    'fetiche', 'zoofilia', 'necrofilia', 'pedofilia', 'pederastia',
    'incesto', 'incestuoso', 'voyeur', 'exhibicionismo',
    
    // ===== ORIENTACIÓN SEXUAL COMO INSULTO =====
    'maricón', 'maricon', 'marica', 'joto', 'puto', 'soplanucas',
    'trolo', 'sarasa', 'torta', 'bollera', 'machorra',
    
    // ===== ACOSO SEXUAL =====
    'te voy a violar', 'te la meto', 'te cojo', 'te follo',
    'mándame nudes', 'mandar nudes', 'pack', 'fotos desnuda',
    'quiero verte desnuda', 'enseña las tetas', 'muestra el culo',
    'estás buena', 'qué rico culo', 'qué buenas tetas',
    
    // ===== AMENAZAS =====
    'te mato', 'te mueres', 'muérete', 'suicídate', 'ojalá te mueras',
    'te voy a', 'te rompo', 'te parto', 'te cago',
    
    // ===== DROGAS (CONTEXTO OFENSIVO) =====
    'drogadicto', 'marihuanero', 'cocainómano', 'adicto de mierda',
    
    // ===== XENOFOBIA =====
    'extranjero de mierda', 'vete a tu país', 'invasor', 'apátrida',
    
    // ===== EXPRESIONES SEXUALES VULGARES =====
    'te la chupo', 'chúpamela', 'chupamela', 'cómemela',
    'me la chupas', 'lámeme', 'métela', 'sácala',
    'estoy caliente', 'me pones cachondo', 'estoy excitado',
    'me mojé', 'me paré', 'tengo ganas', 'quiero sexo',
    
    // ===== INSULTOS CON REFERENCIAS SEXUALES =====
    'come pollas', 'come vergas', 'chupa pijas', 'traga sables',
    'abre patas', 'cualquiera', 'facilita', 'zorra barata',
    'puta de mierda', 'hijo de la gran puta'
];

// ==========================================
// PATRONES REGEX AVANZADOS
// ==========================================
const patronesProhibidos = [
    // ===== Detectar variaciones con números =====
    /p[u3][t7][a4@]/gi,              // puta, put4, p3ta, etc.
    /m[i1][e3]rd[a4@]/gi,             // mierda, m1erda, mi3rda, etc.
    /c[o0]ñ[o0]/gi,                   // coño, c0ño, etc.
    /[ck][a4@]br[o0]n/gi,             // cabron, k4bron, etc.
    /h[u3][e3]v[o0]n/gi,              // huevon, hu3von, etc.
    /p[e3]nd[e3]j[o0]/gi,             // pendejo, p3ndejo, etc.
    /m[a4@]r[i1][ck][o0]n/gi,         // maricon, mar1con, etc.
    /[ck][o0]j[u3]d[o0]/gi,           // cojudo, kojudo, etc.
    
    // ===== Detectar con espacios intermedios =====
    /p\s*u\s*t\s*[a4@]/gi,            // p u t a
    /m\s*[i1]\s*[e3]\s*r\s*d\s*[a4@]/gi, // m i e r d a
    /s\s*[e3]\s*x\s*[o0]/gi,          // s e x o
    
    // ===== Detectar con caracteres especiales =====
    /p[\W_]*u[\W_]*t[\W_]*[a4@]/gi,   // p*u*t*a, p_u_t_a
    /s[\W_]*[e3][\W_]*x[\W_]*[o0]/gi, // s*e*x*o
    
    // ===== Insultos con "de mierda" =====
    /\w+\s+de\s+mierda/gi,
    
    // ===== Frases ofensivas completas =====
    /hijo\s+de\s+put[a4@]/gi,
    /concha\s+tu\s+madre/gi,
    /la\s+concha\s+de/gi,
    /vete\s+a\s+la\s+mierda/gi,
    /chinga\s+tu\s+madre/gi,
    
    // ===== CTM, HDJ, etc. =====
    /\bc[\s\W_]*t[\s\W_]*m\b/gi,
    /\bh[\s\W_]*d[\s\W_]*p\b/gi,
    /\bp[\s\W_]*t[\s\W_]*m\b/gi,
    
    // ===== Amenazas =====
    /te\s+mat[o0]/gi,
    /te\s+v[o0]y\s+[a4@]/gi,
    /[o0]jal[a4@]\s+te\s+mu[e3]ras/gi,
    
    // ===== NUEVOS:  Contenido Sexual =====
    /s[e3]x[o0]\s+(oral|anal)/gi,                    // sexo oral, sexo anal
    /h[a4@]c[e3]r\s+s[e3]x[o0]/gi,                  // hacer sexo
    /t[e3]n[e3]r\s+s[e3]x[o0]/gi,                   // tener sexo
    /m[a4@]m[a4@]d[a4@]/gi,                         // mamada
    /p[a4@]j[a4@]/gi,                                // paja
    /p[e3]n[e3]/gi,                                  // pene
    /v[a4@]g[i1]n[a4@]/gi,                          // vagina
    /t[e3]t[a4@]s/gi,                                // tetas
    /c[u3]l[o0]/gi,                                  // culo
    /[ck][o0]g[e3]r/gi,                             // coger
    /f[o0]ll[a4@]r/gi,                              // follar
    /v[i1][o0]l[a4@]r/gi,                           // violar
    /m[a4@]sturb/gi,                                 // masturb (masturbación, masturbar)
    /p[o0]rn[o0]/gi,                                 // porno
    /xxx/gi,                                         // xxx
    /[o0]nlyf[a4@]ns/gi,                            // onlyfans
    /n[u3]d[e3]s/gi,                                 // nudes
    /d[e3]sn[u3]d[a4@]/gi,                          // desnuda, desnudo
    /[e3]xc[i1]t[a4@]/gi,                           // excita, excitado
    /c[a4@]ch[o0]nd[o0]/gi,                         // cachondo
    /c[a4@]l[i1][e3]nt[e3]/gi,                      // caliente (contexto sexual)
    
    // ===== Acoso sexual =====
    /m[a4@]nd[a4@]\s+n[u3]d[e3]s/gi,                // manda nudes
    /[e3]ns[e3]ñ[a4@]\s+(las|tus)\s+t[e3]t[a4@]s/gi, // enseña las tetas
    /m[u3][e3]str[a4@]\s+[e3]l\s+c[u3]l[o0]/gi,     // muestra el culo
    /t[e3]\s+l[a4@]\s+m[e3]t[o0]/gi,                // te la meto
    /t[e3]\s+c[o0]j[o0]/gi,                         // te cojo
    /t[e3]\s+f[o0]ll[o0]/gi,                        // te follo
    
    // ===== Variaciones de órganos sexuales =====
    /p[i1]t[o0]/gi,                                  // pito
    /p[o0]ll[a4@]/gi,                                // polla
    /v[e3]rg[a4@]/gi,                                // verga
    /p[i1]ng[a4@]/gi,                                // pinga
    /p[i1]j[a4@]/gi,                                 // pija
    /ch[o0]ch[o0]/gi,                                // chocho
    /c[o0]nch[a4@]/gi,                               // concha
    /ch[u3]ch[a4@]/gi,                               // chucha
    
    // ===== Actos sexuales =====
    /c[u3]l[e3][a4@]r/gi,                           // culear
    /t[i1]r[a4@]r/gi,                                // tirar (contexto sexual)
    /g[a4@]rch[a4@]r/gi,                            // garchar
    /c[a4@]ch[a4@]r/gi,                             // cachar
    /p[i1]ch[a4@]r/gi,                              // pichar
    /ch[u3]p[a4@]r/gi,                              // chupar (contexto sexual)
    /m[a4@]m[a4@]r/gi,                              // mamar (contexto sexual)
    /l[a4@]m[e3]r/gi                                // lamer (contexto sexual)
];

// ==========================================
// FUNCIÓN DE VALIDACIÓN AVANZADA
// ==========================================
window.validarContenidoOfensivo = function(texto) {
    if (!texto || texto.trim() === '') {
        return { valido: true, palabraEncontrada: null };
    }
    
    const textoLimpio = texto.toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, ""); // Quitar acentos
    
    // 1. Verificar palabras exactas
    for (let palabra of palabrasProhibidas) {
        const regex = new RegExp('\\b' + palabra. replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\b', 'gi');
        if (regex.test(textoLimpio)) {
            return { 
                valido: false, 
                palabraEncontrada:  palabra,
                tipo: 'palabra_exacta'
            };
        }
    }
    
    // 2. Verificar patrones regex
    for (let patron of patronesProhibidos) {
        if (patron.test(textoLimpio)) {
            const match = textoLimpio.match(patron);
            return { 
                valido: false, 
                palabraEncontrada: match ?  match[0] : 'contenido ofensivo',
                tipo: 'patron_detectado'
            };
        }
    }
    
    // 3. Verificar palabras ofensivas ocultas con caracteres especiales
    const soloLetrasNumeros = textoLimpio.replace(/[^a-z0-9\s]/g, '');
    for (let palabra of palabrasProhibidas) {
        if (soloLetrasNumeros. includes(palabra)) {
            return { 
                valido: false, 
                palabraEncontrada: palabra,
                tipo:  'palabra_oculta'
            };
        }
    }
    
    return { valido: true, palabraEncontrada: null };
};

// ==========================================
// MOSTRAR NOTIFICACIÓN DE ERROR ESPECÍFICA
// ==========================================
window.mostrarErrorContenidoOfensivo = function(tipo = 'general') {
    const mensajes = {
        'general': '❌ No se permiten lisuras ni contenido ofensivo',
        'titulo': '❌ El título contiene palabras inapropiadas',
        'contenido': '❌ El contenido contiene lenguaje inapropiado',
        'respuesta': '❌ Tu respuesta contiene lenguaje ofensivo',
        'sexual': '❌ No se permite contenido sexual explícito'
    };
    
    const mensaje = mensajes[tipo] || mensajes['general'];
    
    if (typeof showNotification === 'function') {
        showNotification(mensaje, 'error');
    } else {
        alert(mensaje);
    }
};

console.log('✅ Sistema anti-lisuras inicializado con', palabrasProhibidas.length, 'palabras prohibidas');