document.addEventListener("DOMContentLoaded", function () {
  // Verifica a aba ativa a partir da URL
  const urlParams = new URLSearchParams(window.location.search);
  const activeTab = urlParams.get("tab") || "pedidos"; // Default para 'pedidos'

  // Ativa a aba correta no carregamento
  activateTab(activeTab);

  // Alternar entre categorias
  const categoryTabs = document.querySelectorAll(".category-tab");

  categoryTabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault();

      const category = this.getAttribute("data-category");
      // Atualiza a URL sem recarregar a página
      history.pushState(null, "", `admin.php?tab=${category}`);

      // Ativa a aba selecionada
      activateTab(category);
    });
  });

  // Função para ativar a aba selecionada
  function activateTab(category) {
    // Remove active class from all tabs and sections
    document
      .querySelectorAll(".category-tab")
      .forEach((t) => t.classList.remove("active"));
    document
      .querySelectorAll(".admin-section")
      .forEach((s) => s.classList.remove("active"));

    // Add active class to clicked tab and corresponding section
    const activeTab = document.querySelector(
      `.category-tab[data-category="${category}"]`
    );
    if (activeTab) {
      activeTab.classList.add("active");
      document.getElementById(`${category}-section`).classList.add("active");
    } else {
      // Fallback caso a aba não exista
      document
        .querySelector('.category-tab[data-category="pedidos"]')
        .classList.add("active");
      document.getElementById("pedidos-section").classList.add("active");
    }
  }

  // Alternar entre abas de conteúdo
  const contentTabs = document.querySelectorAll(".content-tab");

  contentTabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      // Remove active class from all tabs and panels
      document
        .querySelectorAll(".content-tab")
        .forEach((t) => t.classList.remove("active"));
      document
        .querySelectorAll(".content-panel")
        .forEach((p) => p.classList.remove("active"));

      // Add active class to clicked tab and corresponding panel
      this.classList.add("active");
      const content = this.getAttribute("data-content");
      document.getElementById(`${content}-panel`).classList.add("active");
    });
  });

  // Formulário de adicionar produto
  const addProdutoBtn = document.querySelector(".add-produto-btn");
  const addProdutoForm = document.querySelector(".add-produto-form");

  if (addProdutoBtn && addProdutoForm) {
    addProdutoBtn.addEventListener("click", function () {
      addProdutoForm.style.display = "block";
      this.style.display = "none";
      addProdutoForm.scrollIntoView({ behavior: "smooth" });
    });

    const cancelProdutoBtn = addProdutoForm.querySelector(".btn-cancel");
    cancelProdutoBtn.addEventListener("click", function () {
      addProdutoForm.style.display = "none";
      addProdutoBtn.style.display = "inline-flex";
    });
  }

  // Formulário de adicionar cupom
  const addCupomBtn = document.querySelector(".add-cupom-btn");
  const addCupomForm = document.querySelector(".add-cupom-form");

  if (addCupomBtn && addCupomForm) {
    addCupomBtn.addEventListener("click", function () {
      addCupomForm.style.display = "block";
      this.style.display = "none";
      addCupomForm.scrollIntoView({ behavior: "smooth" });
    });

    const cancelCupomBtn = addCupomForm.querySelector(".btn-cancel");
    cancelCupomBtn.addEventListener("click", function () {
      addCupomForm.style.display = "none";
      addCupomBtn.style.display = "inline-flex";
    });
  }

  // =============================================
  // Gerenciamento do Carrossel - Atualizado
  // =============================================
  const formCarrossel = document.getElementById("form-carrossel");
  if (formCarrossel) {
    formCarrossel.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      // Desabilitar botão durante o processamento
      const submitBtn = this.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> Enviando...';

      fetch("processa_carrossel.php?action=add", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Imagem adicionada com sucesso!");
            this.reset();
            loadCarrosselImages(); // Recarregar a lista de imagens
          } else {
            alert("Erro: " + data.message);
          }
        })
        .catch((error) => {
          alert("Erro na requisição: " + error);
        })
        .finally(() => {
          submitBtn.disabled = false;
          submitBtn.textContent = "Adicionar Imagem";
        });
    });
  }

 function loadCarrosselImages() {
  fetch("ger_carrossel.php")
    .then((response) => response.text())
    .then((html) => {
      // Atualiza apenas o grid de imagens, não todo o conteúdo
      const grid = document.getElementById("carrossel-grid");
      if (grid) {
        grid.innerHTML = html;
        setupCarrosselActions();
      }
    })
    .catch(error => {
      console.error('Erro ao carregar imagens:', error);
    });
}

  // Configura os eventos para os botões do carrossel
  function setupCarrosselActions() {
    // Botões de remover
    document.querySelectorAll(".item-actions .remover").forEach((btn) => {
      btn.addEventListener("click", function () {
        const id = this.getAttribute("data-id");
        if (
          confirm("Tem certeza que deseja remover esta imagem do carrossel?")
        ) {
          fetch("processa_carrossel.php?action=remove&id=" + id)
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                this.closest(".item-card").remove();
                alert("Imagem removida com sucesso!");
              } else {
                alert("Erro: " + data.message);
              }
            })
            .catch((error) => {
              alert("Erro ao remover imagem: " + error);
            });
        }
      });
    });

    // Botões de editar
    document.querySelectorAll(".item-actions .editar").forEach((btn) => {
      btn.addEventListener("click", function () {
        const id = this.getAttribute("data-id");
        openEditModal("carrossel", id);
      });
    });
  }

  // admin.js - Atualize a função saveCarrosselEdit
  function saveCarrosselEdit(id, formData) {
    formData.append("idImagem", id); // Adiciona o ID ao FormData

    return fetch("processa_carrossel.php", {
      // Remova o ?action=update&id= da URL
      method: "POST",
      body: formData,
    }).then((response) => response.json());
  }

  // Inicializa os eventos quando a página carrega
  if (document.getElementById("carrossel-grid")) {
    setupCarrosselActions();
  }

  // =============================================
  // FUNÇÕES DE EDIÇÃO
  // =============================================

  // Edição de clientes
  document.querySelectorAll(".clientes-table .btn-view").forEach((btn) => {
    btn.addEventListener("click", function () {
      const userId = this.getAttribute("data-id");
      openEditModal("cliente", userId);
    });
  });

  // Edição de pedidos
  document.querySelectorAll(".pedido-actions .editar").forEach((btn) => {
    btn.addEventListener("click", function () {
      const pedidoId =
        this.closest(".pedido-card").querySelector(".pedido-id").textContent;
      openEditModal("pedido", pedidoId.replace("#", ""));
    });
  });

  // Edição de produtos
  document.querySelectorAll(".produto-actions .editar").forEach((btn) => {
    btn.addEventListener("click", function () {
      const produtoId =
        this.closest(".produto-card").querySelector("h3").textContent;
      openEditModal("produto", produtoId);
    });
  });

  // Edição de cupons
  document.querySelectorAll(".cupom-actions .editar").forEach((btn) => {
    btn.addEventListener("click", function () {
      const cupomCodigo =
        this.closest(".cupom-card").querySelector(".cupom-codigo").textContent;
      openEditModal("cupom", cupomCodigo);
    });
  });

  // Edição de itens de conteúdo
  document.querySelectorAll(".item-actions .editar").forEach((btn) => {
    btn.addEventListener("click", function () {
      const itemId = this.closest(".item-card").querySelector("h4").textContent;
      openEditModal("conteudo", itemId);
    });
  });

  // Função para abrir modal de edição
  function openEditModal(type, id) {
    // Criar o modal
    const modalHTML = `
      <div class="modal-overlay">
        <div class="modal-content">
          <div class="modal-header">
            <h3>Editar ${type}: ${id}</h3>
            <button class="btn-action close-modal"><i class="fas fa-times"></i></button>
          </div>
          <div class="modal-body">
            ${getEditForm(type, id)}
          </div>
          <div class="modal-footer">
            <button class="btn-cancel close-modal">Cancelar</button>
            <button class="btn-submit save-edit" data-type="${type}" data-id="${id}">Salvar Alterações</button>
          </div>
        </div>
      </div>
    `;

    // Inserir o modal no DOM
    document.body.insertAdjacentHTML("beforeend", modalHTML);

    // Adicionar eventos aos botões do modal
    document.querySelectorAll(".close-modal").forEach((btn) => {
      btn.addEventListener("click", closeModal);
    });

    document.querySelector(".save-edit").addEventListener("click", saveEdit);
  }

  // Função para fechar o modal
  function closeModal() {
    const modal = document.querySelector(".modal-overlay");
    if (modal) {
      modal.remove();
    }
  }

  // Função para obter o formulário de edição baseado no tipo
  function getEditForm(type, id) {
    switch (type) {
      case "cliente":
        return `
          <form class="edit-form">
            <div class="form-row">
              <div class="form-group">
                <label>Nome:</label>
                <input type="text" value="Nome do Cliente ${id}" required>
              </div>
              <div class="form-group">
                <label>E-mail:</label>
                <input type="email" value="cliente${id}@exemplo.com" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>CPF:</label>
                <input type="text" value="123.456.789-00" required>
              </div>
              <div class="form-group">
                <label>Data Nascimento:</label>
                <input type="date" value="1990-01-01" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Tipo:</label>
                <select>
                  <option value="user">Usuário</option>
                  <option value="admin">Administrador</option>
                </select>
              </div>
              <div class="form-group">
                <label>Status:</label>
                <select>
                  <option value="ativo">Ativo</option>
                  <option value="desativado">Desativado</option>
                </select>
              </div>
            </div>
          </form>
        `;

      case "pedido":
        return `
          <form class="edit-form">
            <div class="form-row">
              <div class="form-group">
                <label>Status:</label>
                <select>
                  <option value="pendente">Pendente</option>
                  <option value="processando">Processando</option>
                  <option value="enviado">Enviado</option>
                  <option value="entregue">Entregue</option>
                  <option value="cancelado">Cancelado</option>
                </select>
              </div>
              <div class="form-group">
                <label>Data:</label>
                <input type="date" value="2023-05-15" required>
              </div>
            </div>
            <div class="form-group">
              <label>Endereço de Entrega:</label>
              <textarea rows="3">Rua Exemplo, 123 - Centro - São Paulo/SP</textarea>
            </div>
            <div class="form-group">
              <label>Observações:</label>
              <textarea rows="2"></textarea>
            </div>
          </form>
        `;

      case "produto":
        return `
          <form class="edit-form">
            <div class="form-row">
              <div class="form-group">
                <label>Nome:</label>
                <input type="text" value="${id}" required>
              </div>
              <div class="form-group">
                <label>Categoria:</label>
                <select>
                  <option>Roupas</option>
                  <option>Acessórios</option>
                  <option>Coleção X</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preço (R$):</label>
                <input type="number" step="0.01" value="129.90" required>
              </div>
              <div class="form-group">
                <label>Estoque:</label>
                <input type="number" value="42" required>
              </div>
            </div>
            <div class="form-group">
              <label>Descrição:</label>
              <textarea rows="4">Descrição detalhada do produto ${id}</textarea>
            </div>
            <div class="form-group">
              <label>Imagem:</label>
              <input type="file" accept="image/*">
              <small>Deixe em branco para manter a imagem atual</small>
            </div>
          </form>
        `;

      case "cupom":
        return `
          <form class="edit-form">
            <div class="form-row">
              <div class="form-group">
                <label>Código:</label>
                <input type="text" value="${id}" required>
              </div>
              <div class="form-group">
                <label>Desconto (%):</label>
                <input type="number" min="1" max="100" value="20" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Validade:</label>
                <input type="date" value="2023-12-31" required>
              </div>
              <div class="form-group">
                <label>Usos Restantes:</label>
                <input type="number" min="0" value="150">
                <small>Deixe em branco para ilimitado</small>
              </div>
            </div>
            <div class="form-group">
              <label>Status:</label>
              <select>
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
              </select>
            </div>
          </form>
        `;

      case "conteudo":
        return `
          <form class="edit-form">
            <div class="form-row">
              <div class="form-group">
                <label>Título:</label>
                <input type="text" value="${id}" required>
              </div>
              <div class="form-group">
                <label>Data:</label>
                <input type="date" value="2023-03-15" required>
              </div>
            </div>
            <div class="form-group">
              <label>Imagem:</label>
              <input type="file" accept="image/*">
              <small>Deixe em branco para manter a imagem atual</small>
            </div>
            <div class="form-group">
              <label>Conteúdo:</label>
              <textarea rows="6">Conteúdo detalhado sobre ${id}</textarea>
            </div>
          </form>
        `;

      case "carrossel":
        return `
          <form class="edit-form">
            <div class="form-row">
              <div class="form-group">
                <label>Status:</label>
                <select name="status">
                  <option value="inativa">Inativa</option>
                  <option value="ativa">Ativa</option>
                  <option value="principal">Principal</option>
                </select>
              </div>
              <div class="form-group">
                <label>Link para produto:</label>
                <select name="idProduto">
                  <option value="">Nenhum</option>
                  ${getProdutosOptions()}
                </select>
              </div>
            </div>
            <div class="form-group">
              <label>Nova Imagem (opcional):</label>
              <input type="file" name="imagem" accept="image/*">
              <small>Deixe em branco para manter a imagem atual</small>
            </div>
          </form>
        `;

      default:
        return "<p>Formulário de edição não disponível para este tipo.</p>";
    }
  }

  function getProdutosOptions() {
    // Você pode fazer uma chamada AJAX para obter os produtos ou
    // renderizar as opções no PHP quando a página carregar
    return ""; // Retornar options HTML aqui
  }

  // Modificar a função saveEdit para incluir o caso do carrossel
  function saveEdit() {
    const type = this.getAttribute("data-type");
    const id = this.getAttribute("data-id");
    const form = document.querySelector(".edit-form");
    const formData = new FormData(form);

    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';

    if (type === "carrossel") {
  saveCarrosselEdit(id, formData)
    .then((data) => {
      if (data.success) {
        alert("Imagem do carrossel atualizada com sucesso!");
        loadCarrosselImages(); // Isso agora só atualiza o grid
        closeModal();
      } else {
        alert("Erro: " + data.message);
        this.disabled = false;
        this.innerHTML = "Salvar Alterações";
      }
    })
    .catch((error) => {
      alert("Erro na requisição: " + error);
      this.disabled = false;
      this.innerHTML = "Salvar Alterações";
    });
} else {
      // Simular envio dos dados para outros tipos
      setTimeout(() => {
        alert(
          `${
            type.charAt(0).toUpperCase() + type.slice(1)
          } ${id} atualizado com sucesso!`
        );
        closeModal();

        // Atualizar a interface conforme necessário
        if (type === "produto") {
          const produtoCard = document
            .querySelector(`.produto-card h3:contains("${id}")`)
            ?.closest(".produto-card");
          // Atualizar os dados no card do produto
        }
      }, 1500);
    }
  }

  // Validação de formulários
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const submitButton = this.querySelector('button[type="submit"]');
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Processando...';
      }

      // Aqui você pode adicionar a lógica para enviar os dados via AJAX
      // Exemplo:
      /*
      fetch('processa_form.php', {
        method: 'POST',
        body: new FormData(this)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Operação realizada com sucesso!');
          this.reset();
          if (this.closest('.add-produto-form')) {
            addProdutoForm.style.display = 'none';
            addProdutoBtn.style.display = 'inline-flex';
          }
          if (this.closest('.add-cupom-form')) {
            addCupomForm.style.display = 'none';
            addCupomBtn.style.display = 'inline-flex';
          }
          // Recarregar dados se necessário
        } else {
          alert('Erro: ' + data.message);
        }
      })
      .catch(error => {
        alert('Erro na requisição: ' + error);
      })
      .finally(() => {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = 'Salvar';
        }
      });
      */

      // Temporário - apenas para demonstração
      setTimeout(() => {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = "Salvar";
        }
        alert("Formulário enviado com sucesso!");
        this.reset();

        if (this.closest(".add-produto-form")) {
          addProdutoForm.style.display = "none";
          addProdutoBtn.style.display = "inline-flex";
        }

        if (this.closest(".add-cupom-form")) {
          addCupomForm.style.display = "none";
          addCupomBtn.style.display = "inline-flex";
        }
      }, 1500);

      e.preventDefault();
    });
  });

  // Ações de estoque
  document.querySelectorAll(".estoque-actions .fa-plus").forEach((btn) => {
    btn.addEventListener("click", function () {
      const stockElement =
        this.closest(".produto-estoque").querySelector("span");
      let stock = parseInt(stockElement.textContent.replace("Estoque: ", ""));
      stockElement.textContent = `Estoque: ${stock + 1}`;

      // Aqui você pode adicionar uma chamada AJAX para atualizar no banco de dados
      // Exemplo:
      // const productId = this.closest('.produto-card').dataset.id;
      // fetch(`atualiza_estoque.php?id=${productId}&action=increment`, { method: 'POST' });
    });
  });

  document.querySelectorAll(".estoque-actions .fa-minus").forEach((btn) => {
    btn.addEventListener("click", function () {
      const stockElement =
        this.closest(".produto-estoque").querySelector("span");
      let stock = parseInt(stockElement.textContent.replace("Estoque: ", ""));
      if (stock > 0) {
        stockElement.textContent = `Estoque: ${stock - 1}`;

        // Aqui você pode adicionar uma chamada AJAX para atualizar no banco de dados
        // Exemplo:
        // const productId = this.closest('.produto-card').dataset.id;
        // fetch(`atualiza_estoque.php?id=${productId}&action=decrement`, { method: 'POST' });
      }
    });
  });

  // Filtro de clientes (se existir na página)
  const clientSearch = document.getElementById("client-search");
  const clientStatusFilter = document.getElementById("client-status-filter");

  if (clientSearch && clientStatusFilter) {
    clientSearch.addEventListener("input", filterClients);
    clientStatusFilter.addEventListener("change", filterClients);

    function filterClients() {
      const status = clientStatusFilter.value;
      const searchTerm = clientSearch.value.toLowerCase();
      const rows = document.querySelectorAll(".clientes-table tbody tr");

      rows.forEach((row) => {
        const rowStatus = row
          .querySelector(".status-badge")
          .classList.contains("ativo")
          ? "ativo"
          : "desativado";
        const rowType = row
          .querySelector('td[data-label="Tipo"]')
          .textContent.toLowerCase();
        const rowText = row.textContent.toLowerCase();

        const statusMatch =
          status === "all" ||
          (status === "admin" && rowType.includes("admin")) ||
          (status !== "admin" && rowStatus === status);
        const searchMatch = searchTerm === "" || rowText.includes(searchTerm);

        if (statusMatch && searchMatch) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    }
  }
});

// Manipula o botão voltar/avançar do navegador
window.addEventListener("popstate", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const activeTab = urlParams.get("tab") || "pedidos";
  document
    .querySelectorAll(".category-tab")
    .forEach((t) => t.classList.remove("active"));
  document
    .querySelectorAll(".admin-section")
    .forEach((s) => s.classList.remove("active"));

  const activeTabElement = document.querySelector(
    `.category-tab[data-category="${activeTab}"]`
  );
  if (activeTabElement) {
    activeTabElement.classList.add("active");
    document.getElementById(`${activeTab}-section`).classList.add("active");
  }
});
