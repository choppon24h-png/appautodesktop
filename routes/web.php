<?php

use App\Core\Router;

// ============================================================
// APPAUTO SaaS — Rotas Completas
// ============================================================

// ---- Públicas (sem autenticação) ----
Router::get("/login",           "AuthController@showLoginForm");
Router::post("/login",          "AuthController@login");
Router::get("/cadastro",        "AuthController@showCadastroForm");
Router::post("/cadastro",       "AuthController@cadastrar");
Router::get("/validar-token",   "AuthController@showValidarToken");
Router::post("/validar-token",  "AuthController@validarToken");
Router::get("/reenviar-token",  "AuthController@reenviarToken");
Router::get("/recuperar-senha", "AuthController@showRecuperarSenha");
Router::post("/recuperar-senha","AuthController@recuperarSenha");

// ---- Protegidas (requerem autenticação) ----
Router::group(["middleware" => "Auth"], function () {

    // Home / Dashboard
    Router::get("/",          "HomeController@index");
    Router::get("/dashboard", "DashboardController@index");
    Router::post("/logout",   "AuthController@logout");

    // Veículos
    Router::get("/veiculos",                     "VeiculosController@index");
    Router::get("/veiculos/adicionar",           "VeiculosController@showAdicionar");
    Router::post("/veiculos/adicionar",          "VeiculosController@adicionar");
    Router::get("/veiculos/consultar-placa",     "VeiculosController@showConsultarPlaca");
    Router::get("/veiculos/api/consultar-placa", "VeiculosController@apiConsultarPlaca");
    Router::post("/veiculos/api/ocr",            "VeiculosController@apiOCR");
    Router::get("/veiculos/{id}",                "VeiculosController@show");
    Router::get("/veiculos/{id}/editar",         "VeiculosController@showEditar");
    Router::post("/veiculos/{id}/editar",        "VeiculosController@editar");
    Router::post("/veiculos/{id}/excluir",       "VeiculosController@excluir");

    // Perfil e Configurações
    Router::get("/perfil",        "PerfilController@index");
    Router::post("/perfil",       "PerfilController@atualizar");
    Router::get("/configuracoes", "ConfiguracoesController@index");

    // Negócio
    Router::get("/negocio/dashboard", "NegocioController@dashboard");
    Router::get("/negocio/clientes",  "NegocioController@clientes");
    Router::get("/negocio/servicos",  "NegocioController@servicos");

    // Admin
    Router::get("/admin/dashboard",            "AdminController@dashboard");
    Router::get("/admin/clientes/pessoas",     "AdminController@clientesPessoas");
    Router::get("/admin/clientes/negocios",    "AdminController@clientesNegocios");
    Router::get("/admin/acessar-como/{id}",    "AdminController@acessarComo");
    Router::get("/admin/acessar-negocio/{id}", "AdminController@acessarNegocio");
    Router::get("/admin/sair-impersonacao",    "AdminController@sairImpersonacao");
    Router::get("/admin/logs",                 "AdminController@logs");
    Router::get("/admin/configuracoes",        "AdminController@configuracoes");
    Router::get("/admin/usuario/{id}",         "AdminController@verUsuario");
    Router::get("/admin/negocio/{id}",         "AdminController@verNegocio");

    // Módulos legados (mantidos da estrutura original)
    Router::get("/clientes",                   "ClientesController@index");
    Router::get("/financeiro/contas-a-pagar",  "FinanceiroController@contasAPagar");
    Router::get("/financeiro/contas-a-receber","FinanceiroController@contasAReceber");
    Router::get("/faturamento",                "FaturamentoController@index");
    Router::get("/integracao",                 "IntegracaoController@index");
});
