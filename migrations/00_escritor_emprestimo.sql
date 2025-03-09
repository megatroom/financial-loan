-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 08, 2025 at 09:07 PM
-- Server version: 8.0.41
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `escritor_emprestimo`
--

-- --------------------------------------------------------

--
-- Table structure for table `acessos`
--

CREATE TABLE `acessos` (
  `id` int NOT NULL,
  `nome` varchar(50) NOT NULL,
  `chave` varchar(50) NOT NULL,
  `grupo` int NOT NULL,
  `pagina` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `arquivos`
--

CREATE TABLE `arquivos` (
  `id` int NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `arquivo` varchar(100) DEFAULT NULL,
  `data_cad` date NOT NULL,
  `registro` varchar(25) NOT NULL,
  `id_reg` int NOT NULL,
  `usuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int NOT NULL,
  `nome` varchar(50) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `data_nasc` date NOT NULL,
  `data_cad` date NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  `pix` varchar(50) DEFAULT NULL,
  `indicacao` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `pessoa` varchar(25) DEFAULT NULL,
  `grupo` varchar(100) DEFAULT NULL,
  `endereco_sec` varchar(75) DEFAULT NULL,
  `nome_sec` varchar(75) DEFAULT NULL,
  `telefone_sec` varchar(25) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `cobrancas`
--

CREATE TABLE `cobrancas` (
  `id` int NOT NULL,
  `cliente` int NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `parcelas` int NOT NULL,
  `juros` decimal(8,2) DEFAULT NULL,
  `multa` decimal(8,2) DEFAULT NULL,
  `data` date NOT NULL,
  `usuario` int NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  `data_venc` date NOT NULL,
  `frequencia` varchar(25) NOT NULL,
  `valor_parcela` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `icone` varchar(100) DEFAULT NULL,
  `logo_rel` varchar(100) DEFAULT NULL,
  `id` int NOT NULL,
  `ativo` varchar(5) DEFAULT NULL,
  `juros` decimal(8,2) DEFAULT NULL,
  `multa` decimal(8,2) DEFAULT NULL,
  `juros_emp` int DEFAULT NULL,
  `taxa_sistema` varchar(20) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `instancia` varchar(100) DEFAULT NULL,
  `dias_aviso` int DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `marca_dagua` varchar(5) DEFAULT NULL,
  `dias_criar_parcelas` varchar(20) DEFAULT NULL,
  `pix_sistema` varchar(50) DEFAULT NULL,
  `saldo_inicial` decimal(8,2) DEFAULT NULL,
  `verificar_pagamentos` varchar(5) DEFAULT NULL,
  `seletor_api` varchar(30) DEFAULT NULL,
  `assinatura` varchar(100) DEFAULT NULL,
  `recursos` varchar(35) DEFAULT NULL,
  `cobrar_automatico` varchar(5) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `public_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `dispositivos`
--

CREATE TABLE `dispositivos` (
  `id` bigint NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `appkey` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `status_api` varchar(100) DEFAULT NULL,
  `horario` datetime DEFAULT NULL,
  `empresa` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `emprestimos`
--

CREATE TABLE `emprestimos` (
  `id` int NOT NULL,
  `cliente` int NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `parcelas` int NOT NULL,
  `juros` decimal(8,2) DEFAULT NULL,
  `multa` decimal(8,2) DEFAULT NULL,
  `data` date NOT NULL,
  `usuario` int NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  `juros_emp` decimal(8,2) DEFAULT NULL,
  `data_venc` date NOT NULL,
  `frequencia` varchar(25) NOT NULL,
  `tipo_juros` varchar(30) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `valor_parcela` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `feriados`
--

CREATE TABLE `feriados` (
  `id` int NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `formas_pgto`
--

CREATE TABLE `formas_pgto` (
  `id` int NOT NULL,
  `nome` varchar(50) NOT NULL,
  `taxa` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `frequencias`
--

CREATE TABLE `frequencias` (
  `id` int NOT NULL,
  `frequencia` varchar(30) NOT NULL,
  `dias` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `grupo_acessos`
--

CREATE TABLE `grupo_acessos` (
  `id` int NOT NULL,
  `nome` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `pagar`
--

CREATE TABLE `pagar` (
  `id` int NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `valor` decimal(8,2) NOT NULL,
  `data` date NOT NULL,
  `data_venc` date NOT NULL,
  `data_pgto` date DEFAULT NULL,
  `usuario_lanc` int NOT NULL,
  `usuario_pgto` int DEFAULT NULL,
  `referencia` varchar(25) DEFAULT NULL,
  `id_ref` int DEFAULT NULL,
  `pago` varchar(5) DEFAULT NULL,
  `obs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `receber`
--

CREATE TABLE `receber` (
  `id` int NOT NULL,
  `cliente` int NOT NULL,
  `referencia` varchar(30) NOT NULL,
  `id_ref` int NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `parcela` int NOT NULL,
  `usuario_lanc` int NOT NULL,
  `usuario_pgto` int DEFAULT NULL,
  `data` date NOT NULL,
  `data_venc` date NOT NULL,
  `data_pgto` date DEFAULT NULL,
  `pago` varchar(5) NOT NULL,
  `forma_pgto` varchar(30) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `hash` varchar(100) DEFAULT NULL,
  `ref_pix` varchar(60) DEFAULT NULL,
  `obs` varchar(255) DEFAULT NULL,
  `frequencia` int DEFAULT NULL,
  `recorrencia` varchar(5) DEFAULT NULL,
  `hash2` varchar(100) DEFAULT NULL,
  `parcela_sem_juros` decimal(8,2) DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `hora_alerta` time DEFAULT NULL,
  `alerta` varchar(5) DEFAULT NULL,
  `cobrar_sempre` varchar(5) DEFAULT NULL,
  `data_alerta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `senha_crip` varchar(130) NOT NULL,
  `nivel` varchar(25) NOT NULL,
  `ativo` varchar(5) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_permissoes`
--

CREATE TABLE `usuarios_permissoes` (
  `id` int NOT NULL,
  `usuario` int NOT NULL,
  `permissao` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acessos`
--
ALTER TABLE `acessos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arquivos`
--
ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cobrancas`
--
ALTER TABLE `cobrancas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feriados`
--
ALTER TABLE `feriados`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `formas_pgto`
--
ALTER TABLE `formas_pgto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frequencias`
--
ALTER TABLE `frequencias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grupo_acessos`
--
ALTER TABLE `grupo_acessos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pagar`
--
ALTER TABLE `pagar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receber`
--
ALTER TABLE `receber`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios_permissoes`
--
ALTER TABLE `usuarios_permissoes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acessos`
--
ALTER TABLE `acessos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `arquivos`
--
ALTER TABLE `arquivos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cobrancas`
--
ALTER TABLE `cobrancas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispositivos`
--
ALTER TABLE `dispositivos`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emprestimos`
--
ALTER TABLE `emprestimos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feriados`
--
ALTER TABLE `feriados`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `formas_pgto`
--
ALTER TABLE `formas_pgto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `frequencias`
--
ALTER TABLE `frequencias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grupo_acessos`
--
ALTER TABLE `grupo_acessos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagar`
--
ALTER TABLE `pagar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receber`
--
ALTER TABLE `receber`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios_permissoes`
--
ALTER TABLE `usuarios_permissoes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
