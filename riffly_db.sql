-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/08/2026 às 12:10
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `riffly_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `estilos`
--

CREATE TABLE `estilos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estilos`
--

INSERT INTO `estilos` (`id`, `nome`) VALUES
(1, 'Indie'),
(2, 'Trap'),
(3, 'Pop');

-- --------------------------------------------------------

--
-- Estrutura para tabela `faixas`
--

CREATE TABLE `faixas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `duracao` varchar(10) NOT NULL,
  `produtor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtores`
--

CREATE TABLE `produtores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `estilo_id` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtores`
--

INSERT INTO `produtores` (`id`, `nome`, `estilo_id`, `preco`) VALUES
(1, 'Diego Almeida', 1, 350.00),
(2, 'Lab Sonora', 2, 480.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `estilos`
--
ALTER TABLE `estilos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `faixas`
--
ALTER TABLE `faixas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produtor_id` (`produtor_id`);

--
-- Índices de tabela `produtores`
--
ALTER TABLE `produtores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estilo_id` (`estilo_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `estilos`
--
ALTER TABLE `estilos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `faixas`
--
ALTER TABLE `faixas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtores`
--
ALTER TABLE `produtores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `faixas`
--
ALTER TABLE `faixas`
  ADD CONSTRAINT `faixas_ibfk_1` FOREIGN KEY (`produtor_id`) REFERENCES `produtores` (`id`);

--
-- Restrições para tabelas `produtores`
--
ALTER TABLE `produtores`
  ADD CONSTRAINT `produtores_ibfk_1` FOREIGN KEY (`estilo_id`) REFERENCES `estilos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
