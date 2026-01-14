document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('input[name="q"]');
    const keyboardOverlay = document.createElement('div');
    keyboardOverlay.id = 'digitalKeyboard';
    keyboardOverlay.className = 'fixed bottom-[-100%] left-0 right-0 bg-white/95 backdrop-blur-3xl border-t border-gray-200 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-[10000] transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] p-4 [&.active]:bottom-0';
    document.body.appendChild(keyboardOverlay);

    const keys = [
        ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '⌫'],
        ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],
        ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Ñ'],
        ['Z', 'X', 'C', 'V', 'B', 'N', 'M', ',', '.', '-'],
        ['Space', 'Enter']
    ];

    function renderKeyboard() {
        keyboardOverlay.innerHTML = `
            <div class="flex justify-between items-center mb-3 max-w-[700px] mx-auto">
                <span class="text-gray-900 font-bold flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center text-sky-600">
                      <i class="ph-keyboard text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black tracking-tight">TECLADO DIGITAL</p>
                    </div>
                </span>
                <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center cursor-pointer hover:bg-red-500 hover:text-white transition-all duration-200" onclick="window.hideVirtualKeyboard()">
                    <i class="ph-x-bold text-sm"></i>
                </div>
            </div>
            <div class="max-w-[700px] mx-auto flex flex-col gap-1.5">
                ${keys.map(row => `
                    <div class="flex justify-center gap-1.5">
                        ${row.map(key => {
            let extraClass = 'flex-1 min-w-[2.5rem]';
            if (key === '⌫') extraClass = 'bg-gray-100 text-gray-700 min-w-[4rem]';
            if (key === 'Space') extraClass = 'flex-[4] min-w-[10rem] bg-gray-50';
            if (key === 'Enter') extraClass = 'bg-sky-500 text-white min-w-[6rem] font-bold shadow-lg shadow-sky-200';

            const baseClass = 'key h-10 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-xs font-semibold cursor-pointer select-none shadow-sm hover:border-sky-300 transition-all active:scale-95 active:bg-gray-100';

            return `<div class="${baseClass} ${extraClass}" data-key="${key}">${key}</div>`;
        }).join('')}
                    </div>
                `).join('')}
            </div>
        `;

        keyboardOverlay.querySelectorAll('.key').forEach(keyNode => {
            keyNode.addEventListener('click', () => {
                const key = keyNode.dataset.key;
                handleKeyPress(key);
            });
        });
    }

    function handleKeyPress(key) {
        if (!searchInput) return;

        if (key === '⌫') {
            searchInput.value = searchInput.value.slice(0, -1);
        } else if (key === 'Space') {
            searchInput.value += ' ';
        } else if (key === 'Enter') {
            searchInput.closest('form').submit();
        } else {
            searchInput.value += key;
        }

        // Trigger input event for potential real-time listeners
        searchInput.dispatchEvent(new Event('input', { bubbles: true }));
        searchInput.focus();
    }

    window.hideVirtualKeyboard = function () {
        keyboardOverlay.classList.remove('active');
    };

    window.showVirtualKeyboard = function () {
        if (window.isVirtualKeyboardEnabled) {
            keyboardOverlay.classList.add('active');
        }
    };

    if (searchInput) {
        searchInput.addEventListener('focus', () => {
            window.showVirtualKeyboard();
        });

        // Hide keyboard when clicking outside
        document.addEventListener('mousedown', (e) => {
            if (!keyboardOverlay.contains(e.target) && e.target !== searchInput) {
                window.hideVirtualKeyboard();
            }
        });
    }

    renderKeyboard();
});
