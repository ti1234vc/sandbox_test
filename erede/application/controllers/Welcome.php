<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	// Carrega os namespaces do e.rede //
	use Rede\Exception\RedeException;

	use Rede\Service\AbstractService;
	use Rede\Service\AbstractTransactionService;
	use Rede\Service\CancelTransactionService;
	use Rede\Service\CaptureTransaction;
	use Rede\Service\CreateTransactionService;
	use Rede\Service\GetTransactionService;

	use Rede\SerializeTrait;
	use Rede\CreateTrait;
	use Rede\RedeSerializable;
	use Rede\Environment;
	use Rede\Address;
	use Rede\Authorization;
	use Rede\Brand;
	use Rede\Capture;
	use Rede\Cart;
	use Rede\Consumer;
	use Rede\Flight;
	use Rede\Iata;
	use Rede\Item;
	use Rede\Passenger;
	use Rede\Phone;
	use Rede\RedeUnserializable;
	use Rede\Refund;
	use Rede\Store;
	use Rede\SubMerchant;
	use Rede\ThreeDSecure;
	use Rede\Transaction;
	use Rede\Url;
	use Rede\eRede;
	use Rede\Additional;

	class Welcome extends CI_Controller {

		public function index() {
			// Tokens e Chaves da Loja e.Rede //
			$PVREDE = "94590232"; // PV Rede
			$TOKENREDE = "7d299fee687248deb5923c972f5f0a15"; // Token Rede

			// Configuração da loja em modo sandbox //
			$store = new Store($PVREDE, $TOKENREDE, Environment::sandbox());

			// Configuração da loja em modo produção //
			// $store = new Store($PVREDE, $TOKENREDE, Environment::production());

			$transaction = (new Transaction(25, 'pedido' . time()))->creditCard(
				'5448280000000007', // Número cartão (Cartão)
				'123', // CVV (Cartão)
				'01', // Mes vencimento (Cartão)
				'2028', // Ano vencimento (Cartão)
				'Gustavo Souza' // Nome dono (Cartão)
			);

			// Configuração de parcelamento //
			$transaction->setInstallments(3);

			// // Configura o 3dSecure para autenticação //
			// $transaction->threeDSecure(ThreeDSecure::DECLINE_ON_FAILURE); // Recusa a transação em caso de falha em alguma parte //
			// // Configura as URL'S de retorno em caso de FALHA/SUCESSO //
			// $transaction->addUrl("https://www.google.com.br", Url::THREE_D_SECURE_SUCCESS);
			// $transaction->addUrl("https://www.youtube.com.br", Url::THREE_D_SECURE_FAILURE);

			// // Realiza a transação com todos os parametros configurados acima //
			// $transaction = (new eRede($store))->create($transaction);

			// if($transaction->getReturnCode() == '220'):
			// 	printf("Redirecionar usuário pra URL de Sucesso");
			// 	echo "<hr>";
			// 	echo "<pre>";
			// 	print_r($transaction);
			// 	echo "</pre>";
			// endif;
			
			// Autoriza a transação
			$transaction = (new eRede($store))->create($transaction);

			if ($transaction->getReturnCode() == '00') {
				printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
			}

		}

		public function feedback_sucesso() {
			echo "TRANSAÇÃO AUTORIZADA";
		}

		public function feedback_falha() {
			echo "TRANSAÇÃO NÃO AUTORIZADA";
		}

	}
