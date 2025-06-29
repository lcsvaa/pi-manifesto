document.addEventListener("DOMContentLoaded", function () {
  // Elementos globais
  const addAddressBtn = document.getElementById("add-address-btn");
  const addressFormContainer = document.getElementById(
    "address-form-container"
  );
  const addressForm = document.getElementById("address-form");
  const cancelAddressBtn = document.getElementById("cancel-address-btn");
  const addressesContainer = document.getElementById("addresses-container");

  // Menu hamburger (se existir)
  const hamburger = document.getElementById("hamburger-menu");
  if (hamburger) {
    hamburger.addEventListener("click", () => {
      document.body.classList.toggle("menu-open");
    });
  }

  // Mostrar formulário de endereço
  if (addAddressBtn) {
    addAddressBtn.addEventListener("click", () => {
      addressForm.reset();
      document.querySelector(
        "#address-form-container .form-title"
      ).textContent = "Adicionar Endereço";
      document.querySelector("#address-form .btn-save").textContent =
        "Salvar Endereço";
      document
        .querySelector('#address-form input[name="address_id"]')
        ?.remove();
      addAddressBtn.style.display = "none";
      addressFormContainer.style.display = "block";
    });
  }

  // Cancelar formulário
  if (cancelAddressBtn) {
    cancelAddressBtn.addEventListener("click", () => {
      addressFormContainer.style.display = "none";
      addAddressBtn.style.display = "block";
    });
  }

  // Envio do formulário de endereço
  if (addressForm) {
    addressForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const addressId = formData.get("address_id");
      formData.append("action", addressId ? "update_address" : "add_address");

      fetch("atualizar_dados.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          if (!response.ok) throw new Error("Erro na rede");
          return response.json();
        })
        .then((data) => {
          if (data.success) {
            showNotification(data.message);
            loadAddresses();
            addressForm.reset();
            addressFormContainer.style.display = "none";
            addAddressBtn.style.display = "block";
          } else {
            showNotification(data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Erro:", error);
          showNotification("Erro ao salvar endereço", "error");
        });
    });
  }

  // Carregar endereços
  function loadAddresses() {
    fetch("carregar_enderecos.php")
      .then((response) => {
        if (!response.ok) throw new Error("Erro ao carregar endereços");
        return response.json();
      })
      .then((data) => {
        if (addressesContainer) {
          addressesContainer.innerHTML =
            data.length > 0
              ? data.map(createAddressCard).join("")
              : '<p class="no-address">Você ainda não cadastrou nenhum endereço.</p>';
        }
      })
      .catch((error) => {
        console.error("Erro:", error);
        showNotification("Erro ao carregar endereços", "error");
      });
  }

  // Criar card de endereço
  function createAddressCard(address) {
    const isPrincipal =
      address.apelidoEndereco &&
      address.apelidoEndereco.includes("(Principal)");

    return `
            <article class="address-card ${isPrincipal ? "principal" : ""}">
                <div class="address-header">
                    <h3>${address.apelidoEndereco || "Endereço"}</h3>
                    <div class="address-actions">
                        <button class="btn-edit" data-id="${
                          address.idEndereco
                        }" aria-label="Editar endereço">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-delete" data-id="${
                          address.idEndereco
                        }" aria-label="Excluir endereço">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="address-content">
                    <p>${address.rua}, ${address.numero}</p>
                    ${
                      address.complemento
                        ? `<p>Complemento: ${address.complemento}</p>`
                        : ""
                    }
                    <p>Bairro: ${address.bairro}</p>
                    <p>${address.cidade} - ${address.cep}</p>
                </div>
                ${isPrincipal ? '<div class="default-badge"></div>' : ""}
            </article>
        `;
  }

  // Editar endereço
  // Editar endereço
  function loadAddressForEdit(addressId) {
    const formData = new FormData();
    formData.append("action", "get_address");
    formData.append("address_id", addressId);
    formData.append(
      "csrf_token",
      document.querySelector('input[name="csrf_token"]').value
    );

    fetch("atualizar_dados.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) throw new Error("Erro na rede");
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          const address = data.address;
          const apelido = address.apelidoEndereco || "";

          document.getElementById("address-name").value = apelido.replace(
            "(Principal) ",
            ""
          );
          document.getElementById("address-cep").value = address.cep;
          document.getElementById("address-street").value = address.rua;
          document.getElementById("address-number").value = address.numero;
          document.getElementById("address-complement").value =
            address.complemento || "";
          document.getElementById("address-neighborhood").value =
            address.bairro;
          document.getElementById("address-city").value = address.cidade;
          document.getElementById("address-default").checked =
            apelido.includes("(Principal)");

          document.querySelector(
            "#address-form-container .form-title"
          ).textContent = "Editar Endereço";
          document.querySelector("#address-form .btn-save").textContent =
            "Atualizar Endereço";

          let idInput = document.querySelector(
            '#address-form input[name="address_id"]'
          );
          if (!idInput) {
            idInput = document.createElement("input");
            idInput.type = "hidden";
            idInput.name = "address_id";
            addressForm.appendChild(idInput);
          }
          idInput.value = addressId;

          addAddressBtn.style.display = "none";
          addressFormContainer.style.display = "block";
        } else {
          showNotification(data.message, "error");
        }
      })
      .catch((error) => {
        console.error("Erro:", error);
        showNotification("Erro ao carregar endereço", "error");
      });
  }

  // Deletar endereço
  function deleteAddress(addressId) {
    if (confirm("Tem certeza que deseja excluir este endereço?")) {
      const formData = new FormData();
      formData.append("action", "delete_address");
      formData.append("address_id", addressId);
      formData.append(
        "csrf_token",
        document.querySelector('input[name="csrf_token"]').value
      );

      fetch("atualizar_dados.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          if (!response.ok) throw new Error("Erro na rede");
          return response.json();
        })
        .then((data) => {
          if (data.success) {
            showNotification(data.message);
            loadAddresses();
          } else {
            showNotification(data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Erro:", error);
          showNotification("Erro ao excluir endereço", "error");
        });
    }
  }

  // Delegation para botões dinâmicos
  document.addEventListener("click", function (e) {
    // Editar
    if (e.target.closest(".btn-edit")) {
      const btn = e.target.closest(".btn-edit");
      loadAddressForEdit(btn.getAttribute("data-id"));
    }

    // Deletar
    if (e.target.closest(".btn-delete")) {
      const btn = e.target.closest(".btn-delete");
      deleteAddress(btn.getAttribute("data-id"));
    }
  });

  // Inicialização
  if (document.querySelector('[data-tab="addresses"]')) {
    document
      .querySelector('[data-tab="addresses"]')
      .addEventListener("click", loadAddresses);
  }
});

function showNotification(message, type = "success") {
  const notification = document.createElement("div");
  notification.className = `notification ${type}`;
  notification.textContent = message;
  document.body.appendChild(notification);
  setTimeout(() => notification.remove(), 3000);
}

// Configuração do formulário de dados pessoais
function setupPersonalDataForm() {
  const form = document.getElementById("personal-data-form");
  if (!form) return;

  // Armazena valores originais para cancelamento
  const originalValues = {
    name: document.getElementById("name").value,
    email: document.getElementById("email").value,
    phone: document.getElementById("phone").value,
    birthdate: document.getElementById("birthdate").value,
  };

  // Máscara para telefone
  const phoneInput = document.getElementById("phone");
  phoneInput.addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, "");
    if (value.length > 2) {
      value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
    }
    if (value.length > 10) {
      value = `${value.substring(0, 10)}-${value.substring(10, 14)}`;
    }
    e.target.value = value;
  });

  // Botão cancelar - restaura valores originais
  form.querySelector(".btn-cancel")?.addEventListener("click", () => {
    document.getElementById("name").value = originalValues.name;
    document.getElementById("email").value = originalValues.email;
    document.getElementById("phone").value = originalValues.phone;
    document.getElementById("birthdate").value = originalValues.birthdate;
    showNotification("Alterações canceladas", "info");
  });

  // Envio do formulário
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Validação da idade (mínimo 18 anos)
    const birthdate = new Date(document.getElementById("birthdate").value);
    const today = new Date();
    let age = today.getFullYear() - birthdate.getFullYear();
    const monthDiff = today.getMonth() - birthdate.getMonth();

    if (
      monthDiff < 0 ||
      (monthDiff === 0 && today.getDate() < birthdate.getDate())
    ) {
      age--;
    }

    if (age < 18) {
      showNotification("Você deve ter pelo menos 18 anos", "error");
      return;
    }

    const formData = new FormData(this);
    formData.append("action", "update_personal_data");

    fetch("atualizar_dados.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) throw new Error("Erro na rede");
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          // Atualiza valores na interface
          document
            .querySelectorAll(".profile-name, .username")
            .forEach((el) => {
              el.textContent = formData.get("name");
            });
          document.querySelectorAll(".profile-email").forEach((el) => {
            el.textContent = formData.get("email");
          });

          // Atualiza valores originais
          originalValues.name = formData.get("name");
          originalValues.email = formData.get("email");
          originalValues.phone = formData.get("phone");
          originalValues.birthdate = formData.get("birthdate");

          showNotification(data.message);
        } else {
          showNotification(data.message, "error");
        }
      })
      .catch((error) => {
        console.error("Erro:", error);
        showNotification("Erro ao atualizar dados", "error");
      });
  });
}

// Mostrar/Ocultar Senha
document.addEventListener("click", function(e) {
    if (e.target.closest(".toggle-password")) {
        const toggleBtn = e.target.closest(".toggle-password");
        const targetId = toggleBtn.getAttribute("data-target");
        const passwordInput = document.getElementById(targetId);
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            passwordInput.type = "password";
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }
});

// Enviar formulário de troca de senha
document.getElementById("change-password-form")?.addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append("action", "change_password");
    
    // Validação básica
    const newPassword = formData.get("new_password");
    const confirmPassword = formData.get("confirm_password");
    
    if (newPassword !== confirmPassword) {
        showNotification("As senhas não coincidem!", "error");
        return;
    }
    
    fetch("atualizar_dados.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message);
            this.reset();
        } else {
            showNotification(data.message, "error");
        }
    })
    .catch(error => {
        console.error("Erro:", error);
        showNotification("Erro ao alterar senha", "error");
    });
});

// Adicione esta chamada no final do DOMContentLoaded
document.addEventListener("DOMContentLoaded", function () {
  setupPersonalDataForm();
  // ... outras inicializações
});
