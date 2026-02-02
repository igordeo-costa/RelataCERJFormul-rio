# RelataCERJ – Formulário de Relato de Excursões

Plugin WordPress desenvolvido para o **Centro Excursionista Rio de Janeiro (CERJ)** com o objetivo de padronizar, organizar e facilitar o registro de relatos de excursões realizadas pelo clube.

O plugin disponibiliza um formulário público, acessível via shortcode, focado em **consistência dos dados, usabilidade e compatibilidade mobile**, permitindo posterior análise, arquivamento e geração de relatórios.

---

## 📖 Visão Geral

O **RelataCERJ** permite que guias e responsáveis técnicos registrem informações completas sobre excursões, incluindo:

- Dados gerais da atividade
- Participantes
- Condições ambientais e climáticas
- Horários e logística
- Observações técnicas relevantes

Os dados enviados são tratados e normalizados, possibilitando exportação em formato **CSV** para uso administrativo ou técnico.

---

## ✨ Funcionalidades

- 📄 Formulário público via shortcode `[relato_excursao]`
- 🧭 Campos estruturados e padronizados
- 📋 Lista suspensa para **Categoria de Atividade**
- 📱 Interface responsiva (desktop e mobile)
- 🔐 Proteção contra envios indevidos com `wp_nonce_field`
- 🧩 Código simples, organizado e fácil de estender
- 📤 Exportação dos dados em arquivo CSV

---

## 🧩 Estrutura do Plugin

```text
relatacerj-formulario/
├── assets/
│   ├── autocomplete.js          # Script de apoio para preenchimento automático
│   └── form.css                 # Script com a estética
│
├── includes/
│   ├── handler.php              # Validação, processamento e normalização dos dados
│   └── relato.excursao.php      # Estrutura e renderização do formulário
│
├── relatacerj-formulario.php    # Arquivo principal do plugin
└── README.md                    # Documentação do projeto
```

---

## 🔧 Instalação

1. Copie a pasta do plugin para o diretório:
   ```
   wp-content/plugins/relatacerj-formulario/
   ```
2. Acesse o painel administrativo do WordPress
3. Ative o plugin em **Plugins → Plugins Instalados**
4. Crie ou edite uma página e insira o shortcode:
   ```
   [relato_excursao]
   ```

---

## 🚀 Uso

Após inserir o shortcode, o formulário será exibido automaticamente na página.

O processamento dos dados é realizado pelos arquivos em `includes/`, responsáveis por:

- Renderizar o formulário
- Validar o envio dos dados
- Normalizar datas e horários
- Preparar os dados para armazenamento
- Gerar o arquivo CSV

📂 O arquivo CSV é salvo em:
```
wp-content/uploads/
```

---

## 🛠️ Requisitos

- WordPress **6.x** ou superior  
- PHP **8.0** ou superior  

---

## 🔒 Segurança

O plugin utiliza mecanismos nativos do WordPress para segurança, incluindo:

- Verificação de nonce (`wp_nonce_field`)
- Sanitização básica dos dados enviados

---

## 📄 Licença

Projeto de uso **interno e institucional** do Centro Excursionista Rio de Janeiro. Qualquer reprodução não autorizada implica no pagamento obrigatório de **uma cerveja artesanal ao autor do projeto**. 🍺

---

## 👤 Autor

**Igor de Oliveira Costa**  
Auxiliar da Diretoria Técnica do CERJ Biênio **2026–2028**
