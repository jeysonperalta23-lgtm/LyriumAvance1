
		document.addEventListener("DOMContentLoaded", function () {
			var btnVolver = document.querySelector(".volver-btn"); // Reemplaza con la clase real de tu botón
			if (btnVolver) {
				btnVolver.addEventListener("click", function (event) {
					event.preventDefault(); // Evita comportamiento predeterminado
					if (document.referrer) {
						window.location.href = document.referrer; // Redirige a la página anterior
					} else {
						window.location.href = "https://lyriumbiomarketplace.com/"; // Página de inicio en caso de no haber historial
					}
				});
			}
		});
	
		document.addEventListener("DOMContentLoaded", function () {
			var btnCliente = document.querySelector("#btn-cliente");
			var btnVendedor = document.querySelector("#btn-vendedor");
			var contenidoCliente = document.querySelector("#contenido-cliente");
			var contenidoVendedor = document.querySelector("#contenido-vendedor");
			var btnDescargarCliente = document.querySelector("#descargar_tc_cliente");
			var btnDescargarVendedor = document.querySelector("#descargar_tc_vendedor");

			if (btnCliente && btnVendedor && contenidoCliente && contenidoVendedor && btnDescargarCliente && btnDescargarVendedor) {
				// Mostrar la información de clientes y su botón por defecto
				contenidoCliente.style.display = "block";
				contenidoVendedor.style.display = "none";
				btnDescargarCliente.style.display = "block";
				btnDescargarVendedor.style.display = "none";

				btnCliente.addEventListener("click", function () {
					contenidoCliente.style.display = "block";
					contenidoVendedor.style.display = "none";
					btnDescargarCliente.style.display = "block";
					btnDescargarVendedor.style.display = "none";
				});

				btnVendedor.addEventListener("click", function () {
					contenidoCliente.style.display = "none";
					contenidoVendedor.style.display = "block";
					btnDescargarCliente.style.display = "none";
					btnDescargarVendedor.style.display = "block";
				});
			}
		});
	
		document.addEventListener("DOMContentLoaded", function () {
			document.querySelector("#descargar_tc_cliente").addEventListener("click", function () {
				var link = document.createElement("a");
				link.href = "https://lyriumbiomarketplace.com/wp-content/uploads/2025/05/TYC-CLIENTES-1.pdf"; // URL del PDF
				link.download = "https://lyriumbiomarketplace.com/blog/cliente.pdf"; // Nombre del archivo al descargar
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
			});

			document.querySelector("#descargar_tc_vendedor").addEventListener("click", function () {
				var link = document.createElement("a");
				link.href = "https://lyriumbiomarketplace.com/wp-content/uploads/2025/05/TYC-SELLERS.pdf"; // URL del PDF
				link.download = "https://lyriumbiomarketplace.com/blog/vendedor.pdf"; // Nombre del archivo al descargar
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
			});
		});
	
		document.addEventListener("DOMContentLoaded", function () {
			let elemento = document.querySelector(".item.store-count");
			if (elemento) {
				let texto = elemento.innerText.match(/\d+/); // Extrae el número
				elemento.innerText = "Total de Tiendas Registradas: " + (texto ? texto[0] : "");
			}
		});

	
		document.addEventListener("DOMContentLoaded", function () {
			const menuItems = document.querySelectorAll("#menu-productos1 .elementor-sub-item.menu-link");

			menuItems.forEach(item => {
				item.addEventListener("click", function () {
					// Remueve la clase "active" de todos los elementos
					menuItems.forEach(el => el.classList.remove("active"));

					// Agrega la clase "active" solo al elemento clickeado
					this.classList.add("active");
				});
			});
		});

	
		document.addEventListener("DOMContentLoaded", function () {
			let menuProductos = document.querySelector("#menu-productos1");
			if (!menuProductos) return; // Si no encuentra el menú, se detiene aquí

			let categorias = menuProductos.querySelectorAll(".elementor-nav-menu.sm-vertical > li > a");

			categorias.forEach((categoria) => {
				let lastClick = 0; // Guarda el tiempo del último clic

				categoria.addEventListener("click", function (event) {
					let now = new Date().getTime(); // Obtiene el tiempo actual
					let timeDiff = now - lastClick; // Calcula la diferencia de tiempo entre clics
					lastClick = now; // Actualiza el último clic

					event.preventDefault(); // Evita la redirección inmediata

					let subcategorias = this.nextElementSibling; // Obtiene las subcategorías (suponiendo que son un `ul`)

					if (timeDiff < 400) {
						// Si el tiempo entre clics es menor a 400ms, lo considera doble clic -> Redirige
						window.location.href = this.getAttribute("href");
					} else {
						// Si es un solo clic -> Expande o repliega subcategorías
						if (subcategorias && subcategorias.tagName === "UL") {
							subcategorias.classList.toggle("visible");
						}
					}
				});
			});
		});


	
		document.addEventListener("DOMContentLoaded", function () {
			let menuProductos = document.querySelector("#menu-servicios1");
			if (!menuProductos) return; // Si no encuentra el menú, se detiene aquí

			let categorias = menuProductos.querySelectorAll(".elementor-nav-menu.sm-vertical > li > a");

			categorias.forEach((categoria) => {
				let lastClick = 0; // Guarda el tiempo del último clic

				categoria.addEventListener("click", function (event) {
					let now = new Date().getTime(); // Obtiene el tiempo actual
					let timeDiff = now - lastClick; // Calcula la diferencia de tiempo entre clics
					lastClick = now; // Actualiza el último clic

					event.preventDefault(); // Evita la redirección inmediata

					let subcategorias = this.nextElementSibling; // Obtiene las subcategorías (suponiendo que son un `ul`)

					if (timeDiff < 400) {
						// Si el tiempo entre clics es menor a 400ms, lo considera doble clic -> Redirige
						window.location.href = this.getAttribute("href");
					} else {
						// Si es un solo clic -> Expande o repliega subcategorías
						if (subcategorias && subcategorias.tagName === "UL") {
							subcategorias.classList.toggle("visible");
						}
					}
				});
			});
		});




	
		document.addEventListener("DOMContentLoaded", function () {
			let menus = document.querySelectorAll(".elementor-nav-menu.sm-vertical"); // Selecciona todos los menús

			menus.forEach(menu => {
				let items = Array.from(menu.children);

				items.sort((a, b) => {
					let textA = a.textContent.trim().toLowerCase();
					let textB = b.textContent.trim().toLowerCase();
					return textA.localeCompare(textB);
				});

				items.forEach(item => menu.appendChild(item)); // Reorganiza los elementos en cada menú
			});
		});


	

	
		document.addEventListener("DOMContentLoaded", function () {
			const menu = document.getElementById("menu-productos1");

			if (!menu) return;

			let clickTimeout = null;

			menu.addEventListener("click", function (event) {
				let secondLevelCategory = event.target.closest(".elementor-sub-item.menu-link.has-submenu");
				let thirdLevelCategory = event.target.closest(".elementor-sub-item.menu-link:not(.has-submenu)");

				// ✅ Si se hizo clic en una categoría de 3er nivel, redirige inmediatamente
				if (thirdLevelCategory) {
					window.location.href = thirdLevelCategory.href;
					return;
				}

				// ✅ Si se hizo clic en una categoría de 2do nivel
				if (secondLevelCategory) {
					event.preventDefault(); // Evita la redirección inmediata en primer clic

					let parentLi = secondLevelCategory.parentElement; // El <li> contenedor de la categoría de 2do nivel
					let subMenu = parentLi.querySelector(".sub-menu.elementor-nav-menu--dropdown.active"); // Subcategorías de 3er nivel

					// ✅ Doble clic rápido en una categoría de 2do nivel → Redirige a su página
					if (clickTimeout) {
						clearTimeout(clickTimeout);
						window.location.href = secondLevelCategory.href;
						return;
					}

					clickTimeout = setTimeout(() => {
						clickTimeout = null;
					}, 300); // Tiempo para detectar doble clic (ajustable)

					// ✅ Si la categoría ya está activa, solo cierra sus propias subcategorías de 3er nivel
					if (parentLi.classList.contains("active")) {
						if (subMenu) {
							subMenu.classList.remove("active"); // Cierra solo sus propias subcategorías de 3er nivel
						}
						parentLi.classList.remove("active");
					} else {
						// ✅ Abrir la categoría sin cerrar otras categorías de segundo nivel
						parentLi.classList.add("active");
					}
				}
			});
		});

	
		document.addEventListener("DOMContentLoaded", function () {
			let dateElements = document.querySelectorAll(".woocommerce-order-overview__date strong, .order-date");

			let months = {
				"enero": "01", "febrero": "02", "marzo": "03", "abril": "04",
				"mayo": "05", "junio": "06", "julio": "07", "agosto": "08",
				"septiembre": "09", "octubre": "10", "noviembre": "11", "diciembre": "12"
			};

			dateElements.forEach(dateElement => {
				let dateText = dateElement.textContent.trim();
				let parts = dateText.split(" ");

				if (parts.length === 3) {
					let month = months[parts[0].toLowerCase()];
					let day = parts[1].replace(",", "").padStart(2, "0");
					let year = parts[2];

					let formattedDate = `${day}/${month}/${year}`;
					dateElement.textContent = formattedDate;
				}
			});
		});



	
		document.addEventListener("DOMContentLoaded", function () {
			let loginForm = document.querySelector(".woocommerce-form.login");
			if (loginForm) {
				let titulo = loginForm.previousElementSibling; // Selecciona el <h2> anterior al formulario
				if (titulo && titulo.tagName === "H2" && titulo.innerText.trim() === "Acceder") {
					titulo.innerText = "Iniciar Sesión";
				}
			}
		});


	
		document.addEventListener("DOMContentLoaded", function () {
			document.querySelectorAll("th").forEach(th => {
				if (th.innerText.trim() === "Debito") {
					th.innerText = "Débito";
				} else if (th.innerText.trim() === "Credito") {
					th.innerText = "Crédito";
				}
			});
		});


	
		document.addEventListener("DOMContentLoaded", function () {
			// Verificar que estamos en el módulo REPORTES y no en órdenes o devoluciones
			let moduloReportes = document.querySelector(".active.reports");

			if (moduloReportes) {
				let igv = 18; // IGV del 18%
				let comision = 15; // Comisión del 15%

				let filas = document.querySelectorAll(".table.table-striped tbody tr");

				filas.forEach(fila => {
					let celdaDebito = fila.querySelector("td:nth-child(5)");

					if (celdaDebito) {
						let precioConComisionDescontada = parseFloat(celdaDebito.innerText.replace(/[^0-9.]/g, ""));

						if (!isNaN(precioConComisionDescontada)) {
							let precioOriginal = precioConComisionDescontada / (1 - (comision / 100));
							let precioSinIgv = precioOriginal / (1 + (igv / 100));
							let valorComision = precioSinIgv * (comision / 100);
							let gananciaReal = precioOriginal - valorComision;

							celdaDebito.innerText = `S/ ${gananciaReal.toFixed(2)}`;
						}
					}
				});
			}
		});

	
		document.addEventListener("DOMContentLoaded", function () {
			let comision = 15; // Comisión del 15%

			// Seleccionar todas las filas de la tabla
			let filas = document.querySelectorAll(".table.table-striped tbody tr");

			filas.forEach(fila => {
				let celdaBalance = fila.querySelector("td:nth-child(7)"); // Balance (Ajusta si es necesario)

				if (celdaBalance) {
					let balanceActual = parseFloat(celdaBalance.innerText.replace(/[^0-9.]/g, ""));

					if (!isNaN(balanceActual)) {
						// Sumamos el 15% para obtener el precio de venta original
						let balanceCorregido = balanceActual / (1 - (comision / 100));

						// Actualizamos la celda de Balance con el valor corregido
						celdaBalance.innerText = `S/ ${balanceCorregido.toFixed(2)}`;
					}
				}
			});
		});


	
		document.addEventListener("DOMContentLoaded", function () {
			// Verificar que estamos en el módulo PEDIDOS
			let moduloPedidos = document.querySelector(".active.products");

			if (moduloPedidos) {
				const igv = 18; // IGV %
				const baselineComision = 15; // Comisión base usada por Dokan

				const filas = document.querySelectorAll(
					".dokan-table.dokan-table-striped.product-listing-table.dokan-inline-editable-table tr"
				);

				filas.forEach(fila => {
					const celdaDebito = fila.querySelector("td:nth-child(8)"); // Columna de ganancia/debito

					if (!celdaDebito) return;

					const rawText = celdaDebito.innerText || "";
					const precioConComisionDescontada = parseFloat(rawText.replace(/[^0-9.]/g, ""));
					if (isNaN(precioConComisionDescontada)) return;

					// 1) Recuperar precio original (sumando el 15% que Dokan descontó)
					const precioOriginal = precioConComisionDescontada / (1 - (baselineComision / 100));

					// 2) Determinar comisión según rangos
					let comision;
					if (precioOriginal <= 400) {
						comision = 15;
					} else if (precioOriginal <= 800) {
						comision = 14;
					} else if (precioOriginal <= 1200) {
						comision = 13;
					} else {
						comision = 12;
					}

					// 3) Aplicar fórmula
					const precioSinIgv = precioOriginal / (1 + (igv / 100));
					const valorComision = precioSinIgv * (comision / 100);
					const gananciaReal = precioOriginal - valorComision;

					// 4) Actualizar celda
					celdaDebito.innerText = `S/ ${gananciaReal.toFixed(2)}`;
				});
			}
		});



	
		jQuery(document).ready(function ($) {
			function copyBillingAddressToShipping() {
				// Obtener el contenido de la dirección de facturación
				let billingContent = $('.dokan-order-billing-address .dokan-panel-body').html();

				// Dividir el contenido en líneas (separadas por <br>)
				let contentParts = billingContent.split('<br>');

				// Obtener solo la dirección (segunda línea)
				let billingAddress = contentParts.length > 1 ? contentParts[1].trim() : '';

				// Copiar solo la dirección en la dirección de envío
				if (billingAddress !== '') {
					$('.dokan-order-shipping-address .dokan-panel-body').html(billingAddress);
				}
			}

			// Ejecutar al cargar la página
			copyBillingAddressToShipping();
		});




	
		jQuery(document).ready(function ($) {
			const IGV = 18; // %
			function parseMoney(text) {
				if (!text) return NaN;
				let s = String(text).replace('https://lyriumbiomarketplace.com/blog/S/', '').replace(/\s+/g, '').trim();
				// eliminar comas de miles y cualquier carácter no numérico salvo el punto decimal
				s = s.replace(/,/g, '');
				s = s.replace(/[^\d.]/g, '');
				return parseFloat(s);
			}

			function obtenerTdTotalDelTercero() {
				const rows = $('.wc-order-totals tr');
				if (rows.length >= 3) {
					// toma el tercer tr (index 2) y busca td.total dentro
					const targetTd = rows.eq(2).find('td.total').first();
					if (targetTd.length) return targetTd;
				}
				// fallback: intenta obtener el último td.total si no hay 3 filas
				const fallback = $('.wc-order-totals td.total').last();
				return fallback.length ? fallback : null;
			}

			function calcularYReemplazar() {
				const tdTotal = obtenerTdTotalDelTercero();
				if (!tdTotal) {
					console.debug('[Ganancia] td.total objetivo no encontrado');
					return;
				}

				const textoTotal = tdTotal.text();
				const totalPedido = parseMoney(textoTotal);
				if (isNaN(totalPedido) || totalPedido <= 0) {
					console.debug('[Ganancia] totalPedido inválido:', textoTotal);
					return;
				}

				// determinar porcentaje de comisión según el total del pedido (puedes cambiar la regla si prefieres basarla en la base sin IGV)
				let comisionPercent;
				if (totalPedido <= 400) {
					comisionPercent = 15;
				} else if (totalPedido <= 800) {
					comisionPercent = 14;
				} else if (totalPedido <= 1200) {
					comisionPercent = 13;
				} else {
					comisionPercent = 12;
				}

				// cálculo preciso
				const valorVenta = totalPedido / (1 + IGV / 100);            // precio sin IGV
				const valorComision = valorVenta * (comisionPercent / 100); // comisión sobre base sin IGV
				const gananciaFinal = totalPedido - valorComision;          // restar comisión del total

				// reemplazar solo los spans de ganancia que no estén dentro del td.total
				let targets = $('.earning-from-order .woocommerce-Price-amount.amount');
				if (!targets.length) {
					// fallback: cualquier span con esa clase que no esté dentro de td.total
					targets = $('.woocommerce-Price-amount.amount').filter(function () {
						return $(this).closest('td.total').length === 0;
					});
				}

				const textoFinal = `S/ ${gananciaFinal.toFixed(2)}`;
				targets.each(function () {
					$(this).text(textoFinal);
				});

				console.debug(`[Ganancia] totalPedido:${totalPedido.toFixed(2)} | valorVenta:${valorVenta.toFixed(6)} | comisión:${comisionPercent}% | valorComision:${valorComision.toFixed(6)} | ganancia:${gananciaFinal.toFixed(2)}`);
			}

			// Ejecutar al cargar
			calcularYReemplazar();

			// Recalcular si la tabla cambia dinámicamente
			const tabla = document.querySelector('.wc-order-totals');
			if (tabla) {
				const observer = new MutationObserver(function (mutations) {
					// pequeño debounce para evitar múltiples ejecuciones rápidas
					if (window._gananciaTimeout) clearTimeout(window._gananciaTimeout);
					window._gananciaTimeout = setTimeout(() => {
						calcularYReemplazar();
					}, 120);
				});
				observer.observe(tabla, { childList: true, subtree: true, characterData: true });
			}
		});


	
		document.addEventListener("DOMContentLoaded", function () {
			let exportButton = document.querySelector("input[name='dokan_order_export_all']");

			if (exportButton) {
				console.log("Botón de exportación detectado.");

				exportButton.addEventListener("click", function (event) {
					event.preventDefault();

					if (typeof window.jspdf === "undefined") {
						console.log("jsPDF no está cargado correctamente.");
						return;
					}

					var doc = new window.jspdf.jsPDF();

					let welcomeMessage = "¡Lyrium Biomarketplace agradece tu esfuerzo!";
					let imgUrl = "https://lyriumbiomarketplace.com/wp-content/uploads/2024/11/LOGOTIPO_LYRIUM_ORIGINAL.png";
					let img = new Image();
					img.crossOrigin = "Anonymous";
					img.src = imgUrl;

					img.onload = function () {
						let imgWidth = 80;
						let imgHeight = 110;
						let xPos = (doc.internal.pageSize.getWidth() - imgWidth) / 2;
						let yPos = -5;

						let canvas = document.createElement("canvas");
						let ctx = canvas.getContext("2d");
						canvas.width = img.width;
						canvas.height = img.height;
						ctx.fillStyle = "white";
						ctx.fillRect(0, 0, canvas.width, canvas.height);
						ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

						let imgData = canvas.toDataURL("image/png");
						doc.addImage(imgData, "PNG", xPos, yPos, imgWidth, imgHeight);

						let messageYPos = yPos + imgHeight - 10;
						doc.setFont("helvetica", "bold");
						doc.setTextColor(176, 208, 65);
						doc.setFontSize(17);
						doc.text(welcomeMessage, doc.internal.pageSize.getWidth() / 2, messageYPos, { align: "center" });

						doc.setFont("helvetica", "bold");
						doc.setTextColor(92, 148, 180);
						doc.setFontSize(16);
						doc.text("Lista de Pedidos", 105, yPos + imgHeight + 15, { align: "center" });

						generarReporte(doc);
					};

					img.onerror = function () {
						console.warn("No se pudo cargar la imagen, generando el PDF sin logo.");
						generarReporte(doc);
					};

					function generarReporte(doc) {
						let startY = 130;
						var tabla = document.querySelector(".dokan-table.dokan-table-striped");
						if (tabla) {
							let filas = tabla.querySelectorAll("tr");
							let data = [];
							let headers = [];

							filas.forEach((fila, index) => {
								let columnas = fila.querySelectorAll("td, th");
								let filaData = [];

								columnas.forEach((columna, colIndex) => {
									if (colIndex < columnas.length - 1) { // Excluye la última columna (Acción)
										filaData.push(columna.textContent.trim());
									}
								});

								if (index === 0) {
									headers = filaData;
								} else {
									data.push(filaData);
								}
							});

							doc.autoTable({
								head: [headers],
								body: data,
								startY: startY,
								theme: "striped",
								styles: {
									font: "helvetica",
									fontSize: 10,
									cellPadding: 2,
									valign: "middle",
									halign: "center",
								},
								headStyles: {
									fillColor: [92, 148, 180],
									textColor: [255, 255, 255],
									fontStyle: "bold",
								},
							});

							let y = doc.autoTable.previous.finalY + 20;
							let footerMessage = "¡Mantente saludable siempre!";
							let footerImgUrl = "https://lyriumbiomarketplace.com/wp-content/uploads/2025/03/LOGOTIPO-CON-SLOGAN-PNG.png";
							let footerImg = new Image();
							footerImg.crossOrigin = "Anonymous";
							footerImg.src = footerImgUrl;

							footerImg.onload = function () {
								let imgWidth = 100;
								let imgHeight = 27;
								let xImg = (doc.internal.pageSize.getWidth() - imgWidth) / 2;

								let requiredSpace = imgHeight + 20;
								let pageHeight = doc.internal.pageSize.getHeight();

								if (y + requiredSpace > pageHeight) {
									doc.addPage();
									y = 20;
								}

								doc.setFontSize(17);
								doc.setTextColor(176, 208, 65);
								doc.text(footerMessage, 105, y, { align: "center" });

								let canvas = document.createElement("canvas");
								let ctx = canvas.getContext("2d");
								canvas.width = footerImg.width;
								canvas.height = footerImg.height;
								ctx.fillStyle = "white";
								ctx.fillRect(0, 0, canvas.width, canvas.height);
								ctx.drawImage(footerImg, 0, 0, canvas.width, canvas.height);

								let footerImgData = canvas.toDataURL("image/png");
								doc.addImage(footerImgData, "PNG", xImg, y + 5, imgWidth, imgHeight);

								doc.save("https://lyriumbiomarketplace.com/blog/Lista-Pedidos_Lyrium.pdf");
								console.log("PDF generado exitosamente.");
							};

							footerImg.onerror = function () {
								console.warn("No se pudo cargar la imagen del pie de página.");
								doc.save("https://lyriumbiomarketplace.com/blog/Lista-Pedidos_Lyrium.pdf");
							};
						} else {
							doc.text("No se encontraron datos para exportar.", 14, startY);
							doc.save("https://lyriumbiomarketplace.com/blog/Lista-Pedidos_Lyrium.pdf");
							console.log("No se encontró la tabla.");
						}
					}
				});
			} else {
				console.log("No se encontró el botón de exportación.");
			}
		});

	
	
		document.addEventListener("DOMContentLoaded", function () {
			let exportButton = document.querySelector("input[name='dokan_order_export_all']");

			if (exportButton) {
				exportButton.value = "Exportar Pedidos";
			}
		});
	
		document.addEventListener("DOMContentLoaded", function () {
			let interval = setInterval(function () {
				let headings = document.querySelectorAll(".dokan-panel-heading strong");
				let addNoteHeading = document.querySelector(".add_note h4");
				let deleteNoteLinks = document.querySelectorAll("a.delete_note");
				let selectOptions = document.querySelectorAll("#order_note_type option");
				let addNoteButton = document.querySelector("input[name='add_order_note']");
				let trackingButton = document.querySelector("input#add-tracking-details");

				// Corrige "DetTodoses generales" → "Datos generales"
				headings.forEach((heading) => {
					if (heading.textContent.includes("DetTodoses generales")) {
						heading.textContent = "Datos generales";
					}
					if (heading.textContent.includes("no eres as del pedido")) {
						heading.textContent = "Notas del pedido";
					}
				});

				// Corrige "Añadir no eres a" → "Añadir Nota"
				if (addNoteHeading && addNoteHeading.textContent.includes("Añadir no eres a")) {
					addNoteHeading.textContent = "Añadir Nota";
				}

				// Corrige opciones del select
				selectOptions.forEach((option) => {
					if (option.textContent.includes("no eres a del cliente")) {
						option.textContent = "Nota al cliente";
					}
					if (option.textContent.includes("no eres a privada")) {
						option.textContent = "Nota privada";
					}
				});

				// Corrige "Eliminar no eres a" → "Eliminar nota" en todos los enlaces de eliminación
				deleteNoteLinks.forEach((link) => {
					if (link.textContent.includes("Eliminar no eres a")) {
						link.textContent = "Eliminar nota";
					}
				});

				// Corrige el botón "Añadir no ere sa" → "Añadir nota"
				if (addNoteButton && addNoteButton.value.includes("Añadir no eres a")) {
					addNoteButton.value = "Añadir nota";
				}

				// Corrige el botón "Añadir detTodoses de seguimient 0" → "Añadir nota de seguimiento"
				if (trackingButton && trackingButton.value.includes("Añadir detTodoses de seguimiento")) {
					trackingButton.value = "Añadir nota de seguimiento";
				}

				// Detiene el intervalo si ya se han realizado todos los cambios
				if (
					document.body.innerHTML.includes("Datos generales") &&
					document.body.innerHTML.includes("Notas del pedido") &&
					document.body.innerHTML.includes("Añadir Nota") &&
					document.body.innerHTML.includes("Nota al cliente") &&
					document.body.innerHTML.includes("Nota privada") &&
					document.body.innerHTML.includes("Eliminar nota") &&
					document.body.innerHTML.includes("Añadir nota de seguimiento")
				) {
					clearInterval(interval);
				}
			}, 100); // Verifica cada 100ms hasta encontrar y corregir los textos
		});


	
		window.addEventListener('load', function () {
			// Seleccionamos todos los posts dentro del widget
			const widgetPosts = document.querySelectorAll('#pafe-post-list.grid_blog .post-entry');

			widgetPosts.forEach(function (post) {
				// Si el post tiene el ID 12521, lo excluimos
				if (post.getAttribute('data-post-id') === '12521') {
					post.style.display = 'none';  // Ocultamos este post
				}
			});
		});



	
		document.addEventListener("DOMContentLoaded", function () {
			"use strict";

			const BUTTON_SELECTOR = ".components-button.woocommerce-table__download-button";

			// --- Helpers de limpieza ---
			function normalizeSpaces(s) {
				return (s || "").replace(/\s+/g, " ").trim();
			}

			// Limpia encabezados quitando textos de orden/tooltip innecesarios
			function cleanHeaderText(raw) {
				if (!raw) return "";
				let t = normalizeSpaces(raw);
				// Quitar patrones comunes que suelen añadirse en botones de ordenar
				t = t.replace(/(Ordenar(\s*por)?|orden(\s*por)?|por\s+Fecha|ascendente|descendente|Orden|Ordenar por|orden en|ordenar en).*/ig, "");
				// Quitar "por" repetido o paréntesis explicativos
				t = t.replace(/\(.*?\)/g, "");
				t = t.replace(/^\:|\:$/g, "");
				t = t.trim();
				// Si quedó vacío, intentar tomar la primera palabra significativa
				if (!t) {
					const parts = normalizeSpaces(raw).split(/\s+/);
					t = parts.length ? parts[0] : raw;
				}
				return t;
			}

			// Detecta fecha en el texto y regresa dd/mm/yy si existe
			function extractAndFormatDate(s) {
				if (!s) return null;
				// Buscar formatos como 09/08/2025 o 9/8/2025 o 2025-08-09
				let m = s.match(/(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/);
				if (m) {
					let parts = m[1].split(/[\/\-]/);
					// asumir formato dd/mm/yyyy o d/m/yyyy
					let d = parts[0].padStart(2, "0");
					let mo = parts[1].padStart(2, "0");
					let y = parts[2];
					if (y.length === 4) y = y.slice(2);
					else y = y.padStart(2, "0");
					return `${d}/${mo}/${y}`;
				}
				// buscar formato tipo "septiembre 8, 2025" -> no confiable para todos los idiomas; ignoramos
				return null;
			}

			// Limpieza general de celdas de cuerpo
			function cleanCellText(raw) {
				let t = normalizeSpaces(raw);
				// 1) Si contiene fecha, devolver fecha formateada
				const date = extractAndFormatDate(t);
				if (date) return date;

				// 2) Si contiene moneda peruana (S/) devolver la parte con S/ y número
				let moneyMatch = t.match(/S\/\s*[\d\.,]+/);
				if (moneyMatch) return moneyMatch[0].replace(/\s+/g, " ");

				// 3) Si es solo número (qty etc) devolver solo número
				let numMatch = t.match(/^\d+$/);
				if (numMatch) return numMatch[0];

				// 4) Si es muy largo y parece explicación (ej. encabezados concatenados en celdas azules),
				//    devolver la primera palabra significativa
				if (t.length > 40) {
					// Si tiene palabras separadas por espacios, tomar las primeras 2
					const parts = t.split(" ");
					if (parts.length >= 2) return (parts[0] + " " + parts[1]).trim();
					return parts[0];
				}

				// 5) Por defecto devolver el texto normalizado
				return t;
			}

			// --- Generador PDF (casi idéntico al tuyo) ---
			function generarPDFDesdeBoton(botón) {
				try {
					if (typeof window.jspdf === "undefined") {
						console.log("jsPDF no está cargado correctamente.");
						return;
					}

					var doc = new window.jspdf.jsPDF();

					let welcomeMessage = "¡Lyrium Biomarketplace agradece tu esfuerzo!";
					let imgUrl = "https://lyriumbiomarketplace.com/wp-content/uploads/2024/11/LOGOTIPO_LYRIUM_ORIGINAL.png";
					let footerImgUrl = "https://lyriumbiomarketplace.com/wp-content/uploads/2025/03/LOGOTIPO-CON-SLOGAN-PNG.png";

					let img = new Image();
					img.crossOrigin = "Anonymous";
					img.src = imgUrl + (imgUrl.indexOf("?") === -1 ? "?_=" + Date.now() : "&_=" + Date.now());

					img.onload = function () {
						let imgWidth = 80;
						let imgHeight = 110;
						let xPos = (doc.internal.pageSize.getWidth() - imgWidth) / 2;
						let yPos = -5;

						let canvas = document.createElement("canvas");
						let ctx = canvas.getContext("2d");
						canvas.width = img.width;
						canvas.height = img.height;
						ctx.fillStyle = "white";
						ctx.fillRect(0, 0, canvas.width, canvas.height);
						ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

						let imgData = canvas.toDataURL("image/png");
						doc.addImage(imgData, "PNG", xPos, yPos, imgWidth, imgHeight);

						let messageYPos = yPos + imgHeight - 10;
						doc.setFont("helvetica", "bold");
						doc.setTextColor(176, 208, 65);
						doc.setFontSize(17);
						doc.text(welcomeMessage, doc.internal.pageSize.getWidth() / 2, messageYPos, { align: "center" });

						doc.setFont("helvetica", "bold");
						doc.setTextColor(92, 148, 180);
						doc.setFontSize(16);
						doc.text("Reporte de Ventas", 105, yPos + imgHeight + 15, { align: "center" });

						generarReporte(doc, footerImgUrl);
					};

					img.onerror = function () {
						console.warn("No se pudo cargar la imagen principal, generando el PDF sin logo.");
						generarReporte(doc, footerImgUrl);
					};

					function generarReporte(doc, footerImgUrl) {
						let startY = 130;
						var tabla = document.querySelector(".dokan-table.dokan-table-striped") ||
							document.querySelector(".woocommerce-table") ||
							document.querySelector("table");

						if (tabla) {
							let filas = tabla.querySelectorAll("tr");
							let data = [];
							let headers = [];

							filas.forEach((fila, index) => {
								let columnas = fila.querySelectorAll("td, th");
								let filaData = [];

								columnas.forEach((columna, colIndex) => {
									// Para encabezado (index==0) limpiar con cleanHeaderText
									let raw = columna.textContent || "";
									if (index === 0) {
										// Excluir última columna si parece acción
										// comprobación simple: si contiene 'Acción' o botones
										if (colIndex === columnas.length - 1) {
											// si contiene palabra 'Acción' o tiene botones, saltar
											const hasBtns = columna.querySelectorAll("button, a").length > 0;
											const headerText = normalizeSpaces(raw);
											if (hasBtns || /acción|acciones|accion/i.test(headerText)) {
												// no incluir
											} else {
												headers.push(cleanHeaderText(raw));
											}
										} else {
											headers.push(cleanHeaderText(raw));
										}
									} else {
										// Celdas del cuerpo: excluir última columna si parece acción (botones)
										if (colIndex === columnas.length - 1) {
											const hasBtns = columna.querySelectorAll("button, a").length > 0;
											if (hasBtns) {
												// excluir columna de acción
											} else {
												filaData.push(cleanCellText(raw));
											}
										} else {
											filaData.push(cleanCellText(raw));
										}
									}
								});

								if (index !== 0 && filaData.length > 0) data.push(filaData);
							});

							// Si headers vacíos intentar thead
							if ((!headers || headers.length === 0) && tabla.querySelectorAll("thead th").length) {
								headers = Array.from(tabla.querySelectorAll("thead th")).map(h => cleanHeaderText(h.textContent));
							}

							// Si aún está vacío, crear headers genéricos basados en número de columnas
							if (!headers || headers.length === 0) {
								const maxCols = data.length ? Math.max(...data.map(r => r.length)) : 0;
								headers = Array.from({ length: maxCols }, (_, i) => `Col ${i + 1}`);
							}

							// Usar autoTable si está disponible (igual que tu original)
							if (doc && typeof doc.autoTable === "function") {
								doc.autoTable({
									head: [headers],
									body: data,
									startY: startY,
									theme: "striped",
									styles: {
										font: "helvetica",
										fontSize: 10,
										cellPadding: 2,
										valign: "middle",
										halign: "center",
									},
									headStyles: {
										fillColor: [92, 148, 180],
										textColor: [255, 255, 255],
										fontStyle: "bold",
									},
								});
							} else {
								// fallback simple si no existe autoTable
								let y = startY;
								doc.setFontSize(10);
								headers.forEach((h, i) => {
									doc.text(String(h).substring(0, 30), 14 + i * 100, y);
								});
								y += 12;
								data.forEach(row => {
									row.forEach((cell, i) => {
										doc.text(String(cell).substring(0, 40), 14 + i * 100, y);
									});
									y += 10;
									if (y > doc.internal.pageSize.getHeight() - 50) { doc.addPage(); y = 40; }
								});
							}

							// Footer
							let y = doc.autoTable && doc.autoTable.previous ? doc.autoTable.previous.finalY + 20 : startY + 20;
							let footerMessage = "¡Mantente saludable siempre!";
							let footerImg = new Image();
							footerImg.crossOrigin = "Anonymous";
							footerImg.src = footerImgUrl + (footerImgUrl.indexOf("?") === -1 ? "?_=" + Date.now() : "&_=" + Date.now());

							footerImg.onload = function () {
								let imgWidth = 100;
								let imgHeight = 27;
								let xImg = (doc.internal.pageSize.getWidth() - imgWidth) / 2;

								let requiredSpace = imgHeight + 20;
								let pageHeight = doc.internal.pageSize.getHeight();

								if (y + requiredSpace > pageHeight) {
									doc.addPage();
									y = 20;
								}

								doc.setFontSize(17);
								doc.setTextColor(176, 208, 65);
								doc.text(footerMessage, doc.internal.pageSize.getWidth() / 2, y, { align: "center" });

								let canvasF = document.createElement("canvas");
								let ctxF = canvasF.getContext("2d");
								canvasF.width = footerImg.width;
								canvasF.height = footerImg.height;
								ctxF.fillStyle = "white";
								ctxF.fillRect(0, 0, canvasF.width, canvasF.height);
								ctxF.drawImage(footerImg, 0, 0, canvasF.width, canvasF.height);

								let footerImgData = canvasF.toDataURL("image/png");
								doc.addImage(footerImgData, "PNG", xImg, y + 5, imgWidth, imgHeight);

								doc.save("https://lyriumbiomarketplace.com/blog/Lista-Reportes_Lyrium.pdf");
								console.log("PDF de reportes generado exitosamente (limpio).");
							};

							footerImg.onerror = function () {
								console.warn("No se pudo cargar la imagen del pie de página.");
								doc.save("https://lyriumbiomarketplace.com/blog/Lista-Reportes_Lyrium.pdf");
							};

						} else {
							doc.text("No se encontraron datos para exportar.", 14, startY);
							doc.save("https://lyriumbiomarketplace.com/blog/Lista-Reportes_Lyrium.pdf");
							console.log("No se encontró la tabla de reportes.");
						}
					}
				} catch (e) {
					console.error("Error al generar PDF:", e);
					alert("Ocurrió un error generando el PDF. Ver consola.");
				}
			}

			// --- Interceptar click para evitar descarga excel ---
			function interceptEvent(e) {
				try {
					const targetBtn = e.target && e.target.closest ? e.target.closest(BUTTON_SELECTOR) : null;
					if (targetBtn) {
						e.preventDefault && e.preventDefault();
						e.stopImmediatePropagation && e.stopImmediatePropagation();
						e.stopPropagation && e.stopPropagation();

						// desactivar temporalmente para evitar race conditions
						try {
							targetBtn.disabled = true;
							if (targetBtn.getAttribute("name")) targetBtn.setAttribute("data-lyrium-name", targetBtn.getAttribute("name")), targetBtn.removeAttribute("name");
							if (targetBtn.getAttribute("href")) targetBtn.setAttribute("data-lyrium-href", targetBtn.getAttribute("href")), targetBtn.removeAttribute("href");
						} catch (err) { }

						generarPDFDesdeBoton(targetBtn);

						setTimeout(function () {
							try {
								targetBtn.disabled = false;
								if (targetBtn.getAttribute("data-lyrium-name")) {
									targetBtn.setAttribute("name", targetBtn.getAttribute("data-lyrium-name"));
									targetBtn.removeAttribute("data-lyrium-name");
								}
								if (targetBtn.getAttribute("data-lyrium-href")) {
									targetBtn.setAttribute("href", targetBtn.getAttribute("data-lyrium-href"));
									targetBtn.removeAttribute("data-lyrium-href");
								}
							} catch (err) { }
						}, 1000);

						return true;
					}
				} catch (err) {
					console.error("Intercept error:", err);
				}
				return false;
			}

			document.addEventListener("pointerdown", interceptEvent, { capture: true, passive: false });
			document.addEventListener("click", interceptEvent, { capture: true, passive: false });

			// Adjuntar listeners directos como redundancia
			function attachDirect() {
				document.querySelectorAll(BUTTON_SELECTOR).forEach(function (btn) {
					if (btn.dataset.lyriumPdfAttached) return;
					btn.addEventListener("click", function (e) {
						e.preventDefault(); e.stopImmediatePropagation && e.stopImmediatePropagation();
						generarPDFDesdeBoton(btn);
					}, { capture: true });
					btn.dataset.lyriumPdfAttached = "1";
				});
			}

			attachDirect();
			const mo = new MutationObserver(function () { attachDirect(); });
			mo.observe(document.body, { childList: true, subtree: true });

			console.log("Interceptor Lyrium PDF activo y limpieza aplicada.");
		});
	

	
		var MICUENTAWEB_LANGUAGE = "es"
	

	
		(function () {
			var c = document.body.className;
			c = c.replace(/woocommerce-no-js/, 'woocommerce-js');
			document.body.className = c;
		})();
	

	
		const lazyloadRunObserver = () => {
			const lazyloadBackgrounds = document.querySelectorAll(`.e-con.e-parent:not(.e-lazyloaded)`);
			const lazyloadBackgroundObserver = new IntersectionObserver((entries) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						let lazyloadBackground = entry.target;
						if (lazyloadBackground) {
							lazyloadBackground.classList.add('e-lazyloaded');
						}
						lazyloadBackgroundObserver.unobserve(entry.target);
					}
				});
			}, { rootMargin: '200px 0px 200px 0px' });
			lazyloadBackgrounds.forEach((lazyloadBackground) => {
				lazyloadBackgroundObserver.observe(lazyloadBackground);
			});
		};
		const events = [
			'DOMContentLoaded',
			'elementor/lazyload/observe',
		];
		events.forEach((event) => {
			document.addEventListener(event, lazyloadRunObserver);
		});
	

	
		(function () {
			'use strict';

			function removeAndBlock() {
				// 1) Elimina los botones ya existentes (por name y por value)
				document.querySelectorAll(
					'input[type="submit"][name="dokan order export filtered"], input[type="submit"][value="Exportar filtrados"]'
				).forEach(function (btn) {
					try { btn.remove(); } catch (e) { btn.style.display = 'none'; btn.disabled = true; }
				});

				// 2) Bloquea clicks futuros (delegación, captura en fase de captura para máxima prioridad)
				document.addEventListener('click', function (e) {
					var t = e.target.closest && e.target.closest('input[type="submit"]');
					if (!t) return;
					var name = t.getAttribute('name') || '';
					var val = t.value || '';
					if (name === 'dokan order export filtered' || val === 'Exportar filtrados') {
						e.preventDefault();
						e.stopImmediatePropagation();
						e.stopPropagation();
						// opcional: desactivar visualmente
						t.disabled = true;
						t.style.pointerEvents = 'none';
						t.style.opacity = '0.4';
						return false;
					}
				}, true);

				// 3) Intercepta submits de formularios que contengan ese botón
				document.addEventListener('submit', function (e) {
					try {
						if (e.target && e.target.querySelector(
							'input[type="submit"][name="dokan order export filtered"], input[type="submit"][value="Exportar filtrados"]'
						)) {
							e.preventDefault();
							e.stopImmediatePropagation();
							e.stopPropagation();
							return false;
						}
					} catch (err) { }
				}, true);
			}

			// Ejecuta de inmediato
			removeAndBlock();

			// Observador para cualquier inserción dinámica (AJAX, re-render, etc.)
			var observer = new MutationObserver(function () {
				removeAndBlock();
			});
			observer.observe(document.body, { childList: true, subtree: true });
		})();
	

	
		document.addEventListener("DOMContentLoaded", function () {
			// Seleccionar encabezados
			document.querySelectorAll(
				'.mega_menu_servicios h1, .mega_menu_servicios h2, .mega_menu_servicios h3, .mega_menu_servicios h4, .mega_menu_servicios h5, .mega_menu_servicios h6,' +
				'.mega_menu_productos h1, .mega_menu_productos h2, .mega_menu_productos h3, .mega_menu_productos h4, .mega_menu_productos h5, .mega_menu_productos h6'
			).forEach(el => {
				// Agregar tooltip solo al encabezado
				el.setAttribute('data-tooltip', el.textContent.trim());
				el.removeAttribute('title');
				el.removeAttribute('aria-label');
				el.removeAttribute('data-original-title');

				// Buscar enlaces internos y limpiar atributos molestos
				const link = el.querySelector('a');
				if (link) {
					link.removeAttribute('title');
					link.removeAttribute('aria-label');
					link.removeAttribute('data-title');         // 🚀 el que te está generando el doble
					link.removeAttribute('data-original-title');
				}
			});
		});
	

	
		(function () {
			const scope = document.getElementById('menubar-234') || document.body;
			const selector = '.elementor-heading-title.elementor-size-default';

			function process() {
				(scope.querySelectorAll(selector) || []).forEach(h2 => {
					const anchor = h2.querySelector('a');
					const target = anchor || h2;
					if (target.dataset.tooltipProcessed) return;

					const texto = target.textContent.trim();
					if (!texto) return;

					// Guardar texto en data-title (usa data-title para tooltip personalizado)
					target.setAttribute('data-title', texto);

					// eliminar title nativo (evita tooltip nativo del navegador)
					if (target.hasAttribute('title')) {
						target.setAttribute('data-title-fallback', target.getAttribute('title'));
						target.removeAttribute('title');
					}

					// marcar procesado
					target.dataset.tooltipProcessed = '1';
				});
			}

			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', process);
			} else {
				process();
			}

			// Observer para cambios dinámicos de Elementor
			const mo = new MutationObserver(process);
			mo.observe(scope === document.body ? document.body : scope, { childList: true, subtree: true });
		})();
	

	
		document.addEventListener("click", function (e) {
			const target = e.target.closest('.fkcart-shopping-link.fkcart-modal-close');
			if (target) {
				e.preventDefault();
				window.location.href = "https://lyriumbiomarketplace.com/cart/";
			}
		});
	
	
		document.addEventListener("DOMContentLoaded", function () {
			let exportButton = document.querySelector("input[name='dokan_statement_export_all']");

			if (exportButton) {
				console.log("Botón de exportación detectado.");

				exportButton.addEventListener("click", function (event) {
					event.preventDefault();

					if (typeof window.jspdf === "undefined") {
						console.log("jsPDF no está cargado correctamente.");
						return;
					}

					var doc = new window.jspdf.jsPDF();

					let welcomeMessage = "¡Lyrium Biomarketplace agradece tu esfuerzo!";
					let imgUrl = "https://lyriumbiomarketplace.com/wp-content/uploads/2024/11/LOGOTIPO_LYRIUM_ORIGINAL.png";
					let img = new Image();
					img.crossOrigin = "Anonymous";
					img.src = imgUrl;

					img.onload = function () {
						let imgWidth = 80;
						let imgHeight = 110;
						let xPos = (doc.internal.pageSize.getWidth() - imgWidth) / 2;
						let yPos = -5;

						let canvas = document.createElement("canvas");
						let ctx = canvas.getContext("2d");
						canvas.width = img.width;
						canvas.height = img.height;
						ctx.fillStyle = "white";
						ctx.fillRect(0, 0, canvas.width, canvas.height);
						ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

						let imgData = canvas.toDataURL("image/png");
						doc.addImage(imgData, "PNG", xPos, yPos, imgWidth, imgHeight);

						let messageYPos = yPos + imgHeight + -10; // Posición después de la imagen
						doc.setFont("helvetica", "bold");
						doc.setTextColor(176, 208, 65);
						doc.setFontSize(17);
						doc.text("¡Lyrium Biomarketplace agradece tu esfuerzo!", doc.internal.pageSize.getWidth() / 2, messageYPos, { align: "center" });

						doc.setFont("helvetica", "bold");
						doc.setTextColor(92, 148, 180);
						doc.setFontSize(16);
						doc.text("Reporte de Ventas", 105, yPos + imgHeight + 15, { align: "center" });

						generarReporte(doc);
					};

					img.onerror = function () {
						console.warn("No se pudo cargar la imagen, generando el PDF sin logo.");
						generarReporte(doc);
					};

					function generarReporte(doc) {
						let startY = 130;
						var tabla = document.querySelector(".table.table-striped");
						if (tabla) {
							let filas = tabla.querySelectorAll("tr");
							let data = [];
							let headers = [];

							filas.forEach((fila, index) => {
								let columnas = fila.querySelectorAll("td, th");
								let filaData = [];
								columnas.forEach((columna) => {
									filaData.push(columna.textContent.trim());
								});
								if (index === 0) {
									headers = filaData;
								} else {
									data.push(filaData);
								}
							});

							doc.autoTable({
								head: [headers],
								body: data,
								startY: startY,
								theme: "striped",
								styles: {
									font: "helvetica",
									fontSize: 10,
									cellPadding: 2,
									valign: "middle",
									halign: "center",
								},
								headStyles: {
									fillColor: [92, 148, 180],
									textColor: [255, 255, 255],
									fontStyle: "bold",
								},
							});

							let y = doc.autoTable.previous.finalY + 20;
							let footerMessage = "¡Mantente saludable siempre!";
							let footerImgUrl = "https://lyriumbiomarketplace.com/wp-content/uploads/2025/03/LOGOTIPO-CON-SLOGAN-PNG.png";
							let footerImg = new Image();
							footerImg.crossOrigin = "Anonymous";
							footerImg.src = footerImgUrl;

							footerImg.onload = function () {
								let imgWidth = 100;
								let imgHeight = 27;
								let xImg = (doc.internal.pageSize.getWidth() - imgWidth) / 2;

								let requiredSpace = imgHeight + 20;
								let pageHeight = doc.internal.pageSize.getHeight();

								if (y + requiredSpace > pageHeight) {
									doc.addPage();
									y = 20;
								}

								doc.setFontSize(17);
								doc.setTextColor(176, 208, 65);
								doc.text(footerMessage, 105, y, { align: "center" });

								// ✅ Aplicamos el fondo blanco correctamente al footer
								let canvas = document.createElement("canvas");
								let ctx = canvas.getContext("2d");
								canvas.width = footerImg.width;
								canvas.height = footerImg.height;
								ctx.fillStyle = "white";
								ctx.fillRect(0, 0, canvas.width, canvas.height);
								ctx.drawImage(footerImg, 0, 0, canvas.width, canvas.height);

								let footerImgData = canvas.toDataURL("image/png");
								doc.addImage(footerImgData, "PNG", xImg, y + 5, imgWidth, imgHeight);

								doc.save("https://lyriumbiomarketplace.com/blog/Reporte-Ventas_Lyrium.pdf");
								console.log("PDF generado exitosamente.");
							};

							footerImg.onerror = function () {
								console.warn("No se pudo cargar la imagen del pie de página.");
								doc.save("https://lyriumbiomarketplace.com/blog/Reporte-Ventas_Lyrium.pdf");
							};
						} else {
							doc.text("No se encontraron datos para exportar.", 14, startY);
							doc.save("https://lyriumbiomarketplace.com/blog/Reporte-Ventas_Lyrium.pdf");
							console.log("No se encontró la tabla.");
						}
					}
				});
			} else {
				console.log("No se encontró el botón de exportación.");
			}
		});
	
		document.addEventListener("DOMContentLoaded", function () {
			// ✅ Verificar que estamos en el módulo de PEDIDOS
			const moduloPedidos = document.querySelector(".active.orders");

			if (!moduloPedidos) return; // Si no está en el módulo pedidos, no hace nada

			const igv = 18; // IGV %

			const filas = document.querySelectorAll(".dokan-table.dokan-table-striped tbody tr");

			filas.forEach(fila => {
				const celdaTotal = fila.querySelector("td:nth-child(3)"); // Columna 3: total del pedido
				const celdaGanancia = fila.querySelector("td:nth-child(4)"); // Columna 4: ganancia a reemplazar
				if (!celdaTotal || !celdaGanancia) return;

				const totalTexto = celdaTotal.innerText || "";
				const totalPedido = parseFloat(totalTexto.replace(/[^0-9.]/g, ""));
				if (isNaN(totalPedido)) return;

				// 1️⃣ Calcular valor venta sin IGV
				const valorVenta = totalPedido / (1 + (igv / 100));

				// 2️⃣ Determinar comisión según rango del total
				let comision;
				if (totalPedido <= 400) {
					comision = 15;
				} else if (totalPedido <= 800) {
					comision = 14;
				} else if (totalPedido <= 1200) {
					comision = 13;
				} else {
					comision = 12;
				}

				// 3️⃣ Calcular valor de la comisión
				const valorComision = valorVenta * (comision / 100);

				// 4️⃣ Calcular ganancia final del vendedor
				const gananciaFinal = totalPedido - valorComision;

				// 5️⃣ Reemplazar valor en la columna 4
				celdaGanancia.innerText = `S/ ${gananciaFinal.toFixed(2)}`;

				console.debug(`Total:${totalPedido} | Comisión:${comision}% | Ganancia:${gananciaFinal}`);
			});
		});
	
	
/* @v3-js:start */
		let c4wp_onloadCallback = function () {
			for (var i = 0; i < document.forms.length; i++) {
				let form = document.forms[i];
				let captcha_div = form.querySelector('.c4wp_captcha_field_div:not(.rendered)');
				let jetpack_sso = form.querySelector('#jetpack-sso-wrap');
				var wcblock_submit = form.querySelector('.wc-block-components-checkout-place-order-button');
				var has_wc_submit = null !== wcblock_submit;

				if (null === captcha_div && !has_wc_submit || form.id == 'create-group-form') {
					if (!form.parentElement.classList.contains('nf-form-layout')) {
						continue;
					}

				}
				if (!has_wc_submit) {
					if (!(captcha_div.offsetWidth || captcha_div.offsetHeight || captcha_div.getClientRects().length)) {
						if (jetpack_sso == null && !form.classList.contains('woocommerce-form-login')) {
							continue;
						}
					}
				}

				let alreadyCloned = form.querySelector('.c4wp-submit');
				if (null != alreadyCloned) {
					continue;
				}

				let foundSubmitBtn = form.querySelector('#signup-form [type=submit], [type=submit]:not(#group-creation-create):not([name="signup_submit"]):not([name="ac_form_submit"]):not(.verify-captcha)');
				let cloned = false;
				let clone = false;

				// Submit button found, clone it.
				if (foundSubmitBtn) {
					clone = foundSubmitBtn.cloneNode(true);
					clone.classList.add('c4wp-submit');
					clone.removeAttribute('onclick');
					clone.removeAttribute('onkeypress');
					if (foundSubmitBtn.parentElement.form === null) {
						foundSubmitBtn.parentElement.prepend(clone);
					} else {
						foundSubmitBtn.parentElement.insertBefore(clone, foundSubmitBtn);
					}
					foundSubmitBtn.style.display = "none";
					captcha_div = form.querySelector('.c4wp_captcha_field_div');
					cloned = true;
				}

				// WC block checkout clone btn.
				if (has_wc_submit && !form.classList.contains('c4wp-primed')) {
					clone = wcblock_submit.cloneNode(true);
					clone.classList.add('c4wp-submit');
					clone.classList.add('c4wp-clone');
					clone.removeAttribute('onclick');
					clone.removeAttribute('onkeypress');
					if (wcblock_submit.parentElement.form === null) {
						wcblock_submit.parentElement.prepend(clone);
					} else {
						wcblock_submit.parentElement.insertBefore(clone, wcblock_submit);
					}
					wcblock_submit.style.display = "none";

					clone.addEventListener('click', function (e) {
						if (form.classList.contains('c4wp_v2_fallback_active')) {
							jQuery(form).find('.wc-block-components-checkout-place-order-button:not(.c4wp-submit)').click();
							return true;
						} else {
							grecaptcha.execute('6LdyKGMrAAAAAOLfTu9DJALC7nTbYRpINMBF-XC8',).then(function (data) {
								form.classList.add('c4wp-primed');
							});
						}

					});
					foundSubmitBtn = wcblock_submit;
					cloned = true;
				}

				// Clone created, listen to its click.
				if (cloned) {
					clone.addEventListener('click', function (event) {
						logSubmit(event, 'cloned', form, foundSubmitBtn);
					});
					// No clone, execute and watch for form submission.
				} else {
					grecaptcha.execute(
						'6LdyKGMrAAAAAOLfTu9DJALC7nTbYRpINMBF-XC8',
					).then(function (data) {
						var responseElem = form.querySelector('.c4wp_response');
						if (responseElem == null) {
							var responseElem = document.querySelector('.c4wp_response');
						}
						if (responseElem != null) {
							responseElem.setAttribute('value', data);
						}
					});

					// Anything else.
					form.addEventListener('submit', function (event) {
						logSubmit(event, 'other', form);
					});
				}

				function logSubmit(event, form_type = '', form, foundSubmitBtn) {
					// Standard v3 check.
					if (!form.classList.contains('c4wp_v2_fallback_active') && !form.classList.contains('c4wp_verified')) {
						event.preventDefault();
						try {
							grecaptcha.execute(
								'6LdyKGMrAAAAAOLfTu9DJALC7nTbYRpINMBF-XC8',
							).then(function (data) {
								var responseElem = form.querySelector('.c4wp_response');
								if (responseElem == null) {
									var responseElem = document.querySelector('.c4wp_response');
								}

								responseElem.setAttribute('value', data);

								if (form.classList.contains('wc-block-checkout__form')) {
									// WC block checkout.
									let input = document.querySelector('input[id*="c4wp-wc-checkout"]');
									let lastValue = input.value;
									var token = data;
									input.value = token;
									let event = new Event('input', { bubbles: true });
									event.simulated = true;
									let tracker = input._valueTracker;
									if (tracker) {
										tracker.setValue(lastValue);
									}
									input.dispatchEvent(event)
								}


								/* @v3-fallback-js:start */
								if (typeof captcha_div == 'undefined' && form.classList.contains('wc-block-checkout__form')) {
									var captcha_div = form.querySelector('#additional-information-c4wp-c4wp-wc-checkout');
								}

								if (!captcha_div && form.classList.contains('wc-block-checkout__form')) {
									var captcha_div = form.querySelector('#order-c4wp-c4wp-wc-checkout');
								}

								if (typeof captcha_div == 'undefined') {
									var captcha_div = form.querySelector('.c4wp_captcha_field_div');
								}

								var parentElem = captcha_div.parentElement;

								if ((form.classList.contains('c4wp-primed')) || (!form.classList.contains('c4wp_verify_underway') && captcha_div.parentElement.getAttribute('data-c4wp-use-ajax') == 'true')) {

									form.classList.add('c4wp_verify_underway');
									const flagMarkup = '<input id="c4wp_ajax_flag" type="hidden" name="c4wp_ajax_flag" value="c4wp_ajax_flag">';
									var flagMarkupDiv = document.createElement('div');
									flagMarkupDiv.innerHTML = flagMarkup.trim();

									form.appendChild(flagMarkupDiv);

									var nonce = captcha_div.parentElement.getAttribute('data-nonce');

									var formData = new FormData();

									formData.append('action', 'c4wp_ajax_verify');
									formData.append('nonce', nonce);
									formData.append('response', data);

									fetch('https://lyriumbiomarketplace.com/wp-admin/admin-ajax.php', {
										method: 'POST',
										body: formData,
									}) // wrapped
										.then(
											res => res.json()
										)
										.then(data => {
											if (data['success']) {
												form.classList.add('c4wp_verified');
												// Submit as usual.
												if (foundSubmitBtn) {
													foundSubmitBtn.click();
												} else if (form.classList.contains('wc-block-checkout__form')) {
													jQuery(form).find('.wc-block-components-checkout-place-order-button:not(.c4wp-submit)').click();
												} else {
													if (typeof form.submit === 'function') {
														form.submit();
													} else {
														HTMLFormElement.prototype.submit.call(form);
													}
												}

											} else {
												//jQuery( '.nf-form-cont' ).trigger( 'nfFormReady' );

												if ('redirect' === 'v2_checkbox') {
													window.location.href = '';
												}

												if ('v2_checkbox' === 'v2_checkbox') {
													if (form.classList.contains('wc-block-checkout__form')) {
														captcha_div = captcha_div.parentElement;
													}

													captcha_div.innerHTML = '';
													form.classList.add('c4wp_v2_fallback_active');
													flagMarkupDiv.firstChild.setAttribute('name', 'c4wp_v2_fallback');

													var c4wp_captcha = grecaptcha.render(captcha_div, {
														'sitekey': '6LepKmMrAAAAAHON4omaPeNdII5Lz5HzeBBuB-Y3',
														'size': 'normal',
														'theme': 'light',
														'expired-callback': function () {
															grecaptcha.reset(c4wp_captcha);
														}
													});
													jQuery('.ninja-forms-field.c4wp-submit').prop('disabled', false);
												}

												if (form.classList.contains('wc-block-checkout__form')) {
													return true;
												}

												if (form.parentElement.classList.contains('nf-form-layout')) {
													jQuery('.ninja-forms-field.c4wp-submit').prop('disabled', false);
													return false;
												}

												// Prevent further submission
												event.preventDefault();
												return false;
											}
										})
										.catch(err => console.error(err));

									// Prevent further submission
									event.preventDefault();
									return false;
								}
								/* @v3-fallback-js:end */


								// Submit as usual.
								if (foundSubmitBtn) {
									foundSubmitBtn.click();
								} else if (form.classList.contains('wc-block-checkout__form')) {
									jQuery(form).find('.wc-block-components-checkout-place-order-button:not(.c4wp-submit)').click();
								} else {

									if (typeof form.submit === 'function') {
										form.submit();
									} else {
										HTMLFormElement.prototype.submit.call(form);
									}
								}

								return true;
							});
						} catch (e) {
							// Silence.
						}
						// V2 fallback.
					} else {
						if (form.classList.contains('wpforms-form') || form.classList.contains('frm-fluent-form') || form.classList.contains('woocommerce-checkout')) {
							return true;
						}

						if (form.parentElement.classList.contains('nf-form-layout')) {
							return false;
						}

						if (form.classList.contains('wc-block-checkout__form')) {
							return;
						}

						// Submit as usual.
						if (typeof form.submit === 'function') {
							form.submit();
						} else {
							HTMLFormElement.prototype.submit.call(form);
						}

						return true;
					}
				};
			}
		};

		grecaptcha.ready(c4wp_onloadCallback);

		if (typeof jQuery !== 'undefined') {
			jQuery('body').on('click', '.acomment-reply.bp-primary-action', function (e) {
				c4wp_onloadCallback();
			});
		}

		//token is valid for 2 minutes, So get new token every after 1 minutes 50 seconds
		setInterval(c4wp_onloadCallback, 110000);


		window.addEventListener("load", (event) => {
			if (typeof jQuery !== 'undefined' && jQuery('input[id*="c4wp-wc-checkout"]').length) {
				var element = document.createElement('div');
				var html = '<div class="c4wp_captcha_field" style="margin-bottom: 10px" data-nonce="b13787a839" data-c4wp-use-ajax="true" data-c4wp-v2-site-key="6LepKmMrAAAAAHON4omaPeNdII5Lz5HzeBBuB-Y3"><div id="c4wp_captcha_field_0" class="c4wp_captcha_field_div"><input type="hidden" name="g-recaptcha-response" class="c4wp_response" aria-label="do not use" aria-readonly="true" value="" /></div></div>';
				element.innerHTML = html;
				jQuery('[class*="c4wp-wc-checkout"]').append(element);
				jQuery('[class*="c4wp-wc-checkout"]').find('*').off();
				c4wp_onloadCallback();
			}
		});
		/* @v3-js:end */
	
