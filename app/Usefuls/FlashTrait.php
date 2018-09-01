<?php

namespace App\Usefuls;

/**
 * Methods useful about flash message
 * and sessions manipulation
 *
 * @author Rodrigo Cachoeira <rodrigocachoeira11@gmail.com>
 * @class FlashTrait
 * @package App\Usefuls
 */
trait FlashTrait
{

    /**
     * @var String
     */
    public  $alreadyExists =
        'Não foi possível vincular o registro, verifique se você não está tentando inserir um registro repetido';

    /**
     * @var String
     */
    public $relatedRecords =
        'Não foi possível excluir o registro, pois há informações relacionadas a ele';

    /**
     * Show success message with flash message after
     * redirect page or not
     *
     * @param string $destiny
     * @param string $message
     * @param bool|true $redirect
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function successMessage($destiny, $message = 'Operação Realizada com sucesso.' , $redirect = true)
    {
        flash()->success( $message );

        if( $redirect )
            return redirect( $this->urlManager->retrieveCache($destiny) );
    }

    /**
     * Show error message with flash message after
     * redirect page or not
     *
     * @param string $destiny
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function fail($destiny, $message)
    {
        flash()->error($message);
        return redirect($destiny)->withInput();
    }

    /**
     * Show error message with flash message after
     * redirect page or not
     *
     * @param string $destiny
     * @param string $message
     * @param bool|true $redirect
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function errorMessage( $destiny, $message = 'Não foi possível realizar a Operação', $redirect = true)
    {
        flash()->error( $message );
        //$this->setOldValues();

        if( $redirect )
            return redirect( $this->urlManager->retrieveCache($destiny) );
    }

    /**
     * Show a Info message with flash message
     * after redirect page or not
     *
     * @param string $destiny
     * @param string $message
     * @param bool|true $redirect
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function infoMessage( $destiny, $message, $redirect = true)
    {
        flash()->info( $message );

        if( $redirect )
            return redirect( $this->urlManager->retrieveCache($destiny) );
    }

    /**
     * Show a Info message with flash message
     * after redirect page or not
     *
     * @param string $destiny
     * @param string $message
     * @param bool|true $redirect
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function infoMessageWithoutCache( $destiny, $message, $redirect = true)
    {
        flash()->info( $message );

        if( $redirect )
            return redirect( $destiny );
    }

    /**
     * Define os valores para uma nova
     * apresentacao em um possivel formulário
     * na página, para o usuário n perder suas
     * informacoes preenchidas
     *
     * @return void
     */
    private function setOldValues ()
    {
        session()->put('_old_input', request()->all());
    }

}
