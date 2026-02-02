# RelataCERJ – Formulário de Relato de Excursões

Plugin WordPress para registro padronizado de **relatos de excursões do Centro Excursionista Rio de Janeiro**, desenvolvido para uso institucional do clube, com foco em clareza, consistência dos dados e compatibilidade mobile.

## 📌 Visão Geral

O **RelataCERJ** disponibiliza um formulário público via shortcode, permitindo que guias registrem informações completas sobre excursões realizadas, incluindo dados operacionais, participantes, condições climáticas e observações relevantes.

Os dados são tratados de forma normalizada, possibilitando exportação posterior (ex.: CSV) para análise, arquivo ou relatórios.

## ✨ Funcionalidades

- Shortcode `[relato_excursao]` para exibição do formulário
- Campos estruturados e documentados para facilitar o preenchimento
- Lista suspensa para **Categoria de Atividade**
- Interface responsiva (desktop e mobile)
- Segurança com `wp_nonce_field`
- Arquitetura simples e extensível

## 🧩 Estrutura do Plugin

```
relatacerj-formulario/
├── relatacerj-formulario.php   # Arquivo principal do plugin
└── includes/
    └── handler.php             # Processamento e normalização dos dados
```

## 🔧 Instalação

1. Copie a pasta do plugin para:
   ```
   wp-content/plugins/relatacerj-formulario/
   ```
2. Ative o plugin no painel administrativo do WordPress
3. Crie uma página e insira o shortcode:
   ```
   [relato_excursao]
   ```

## 🚀 Uso

Após inserir o shortcode, o formulário será exibido automaticamente na página.

O arquivo `handler.php` é responsável por:
- Validar o envio
- Normalizar datas e horários
- Preparar os dados para armazenamento ou exportação
- O arquivo CSV é salvo em:
```
wp-content/uploads/
```

## 🛠️ Requisitos

- WordPress 6.x ou superior
- PHP 8.0 ou superior

## 📄 Licença

Este projeto é de uso interno/institucional. Todos que forem reproduzir devem uma cerveja artesanal para o Autor do projeto.

## 👤 Autor

**Igor de Oliveira Costa**  
Auxiliar da Diretoria Técnica do CERJ, biênio 2026-2028.
