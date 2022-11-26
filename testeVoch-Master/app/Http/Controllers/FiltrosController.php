<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\GrupoEconomico;
use App\Traits\Filtros;
use Illuminate\Http\Request;
use App\Bandeira;
use App\Funcionario;
use App\Unidade;
use App\User;

class FiltrosController extends Controller
{
    private $selects = [
        1 => ["id", "gec_razao_social"],
        2 => ["gec_documento", "gec_nome_fantasia"],
        3 => ["ban_documento", "ban_nome"],
        4 => ["id", "ban_nome"],
        5 => ["uni_documento", "uni_nome_fantasia"],
        6 => ["uni_documento", "uni_razao_social"],
        7 => ["id", "uni_nome_fantasia"],
        8 => ["fun_documento", "fun_nome"],
        9 => ["fun_nome", "uni_nome_fantasia"],
        10 => ["id", "fun_nome"],
    ];

    private $campos = [];
    
    public function index()
    {   
        $filtros = $this->montarFiltros(['gruposEconomicos']);
        return view('pesquisas', compact('filtros'));
    }

    public function filtros(Request $req)
    {
        $this->campos = $this->selects[$req->gruposEconomicosPesquisa];    
        $dados = [];
        
        if(in_array($req->gruposEconomicosPesquisa, [1, 2])) {
            $dados = $this->GrupoEconomicos($req);
        }

        if(in_array($req->gruposEconomicosPesquisa, [3, 4])) {
            $dados = $this->bandeiras($req);
                
        }

        if(in_array($req->gruposEconomicosPesquisa, [5, 6, 7])) {
            $dados = $this->unidades($req);
        }

        if(in_array($req->gruposEconomicosPesquisa, [8, 9, 10])) {
            $dados = $this->funcionarios($req);
        }

        $resultado = [];

        foreach ($dados as $dado) {
            $resultado[] = [
                'id' => $dado->id,
                'text' => $dado->{$this->campos[0]} . '-' . $dado->{$this->campos[1]}
            ];    
        }

        return response()->json([
            'results' => $resultado,
            'pagination' => [
                'more' => $dados->hasMorePages(),
            ],
        ]);
    }
   
    private function GrupoEconomicos($req)
    {
        return GrupoEconomico::select($this->campos)
        ->where('gec_ativo', $req->gruposEconomicosAtividade[0])
        ->where($this->campos[0], "like", "%{$req->pesquisa}%")->first()
        ->orWhere($this->campos[1], "like", "%{$req->pesquisa}%")
        ->paginate();
    }

    private function bandeiras($req)
    {
        return Bandeira::select($this->campos)
        ->where('ban_ativo', $req->gruposEconomicosAtividade[0])
        ->where($this->campos[0], "like", "%{$req->pesquisa}%")->first()
        ->orWhere($this->campos[1], "like", "%{$req->pesquisa}%")
        ->paginate();
    }

    private function unidades($req) 
    {
        return Unidade::select($this->campos)
        ->where('uni_ativo', $req->gruposEconomicosAtividade[0])
        ->where($this->campos[0], "like", "%{$req->pesquisa}%")->first()
        ->orWhere($this->campos[1], "like", "%{$req->pesquisa}%")
        ->paginate();
    }

    private function funcionarios($req)
    {
        return Funcionario::select($this->campos)
        ->where('fun_ativo', $req->gruposEconomicosAtividade[0])
        ->join('unidade', 'funcionario.uni_id', '=', 'unidade.id')->first()
        ->where($this->campos[0], "like", "%{$req->pesquisa}%")
        ->orWhere($this->campos[1], "like", "%{$req->pesquisa}%")
        ->paginate();
    }

    private function montarFiltros(array $campos)
    {
        $filtros['gruposEconomicos'] = [
                'pesquisa' => [
                "1" => [
                        'texto' => 'Código e Nome',
                        'placeholder' => 'Pesquise por código ou nome'],
                "2" => [
                        'texto' => 'Documento e Nome fantasia',
                        'placeholder' => 'Pesquise por nome fantasia, cpf ou cnpj'],
                "3" => [
                        'texto' => 'Ban Documento e Ban Nome',
                        'placeholder' => 'Pesquise por Documento ou Nome'],
                "4" => [
                        'texto' => 'id e Ban Nome',
                        'placeholder' => 'Pesquise por Id ou Nome'],
                "5" => [
                        'texto' => 'Uni Documento e Uni Nome fantasia',
                        'placeholder' => 'Pesquise por Uni Documento ou Uni nome Fantasia',],
                "6" => [
                        'texto' => 'Uni Documento e Uni Razao Social',
                        'placeholder' => 'Pesquise por Uni Documento ou Uni Razao Social',],
                "7" => [
                        'texto' => 'Id e Uni Nome fantasia',
                        'placeholder' => 'Pesquise por Id ou Uni Nome Fantasia',],
                "8" => [
                        'texto' => 'Fun Documento e Fun Nome',
                        'placeholder' => 'Pesquise por Fun Documento ou Fun Nome',],
                "9" => [
                        'texto' => 'Fun Nome e Uni Nome Fantasia',
                        'placeholder' => 'Pesquise por Fun Nome ou Uni Nome Fantasia',],
                "10" => [
                        'texto' => 'Id e Fun Nome',
                        'placeholder' => 'Pesquise por Id ou Fun Nome',],
                
            ],
            'atividade' => [
                1 => 'Ativo',
                0 => 'Inativo',
            ],
            'multiplo' => true,
            'titulo' => 'Grupos Econômicos',
            'nome' => 'gruposEconomicos',
            'placeholder' => '',
            'rota' => route('filtros'),
            ];

        $result = [];
        foreach ($filtros as $filtro => $dados) {
            if (!in_array($filtro, $campos))
                continue;

            $result[$filtro] = view('filtrosAjax', compact('dados'))->render();
        }

        return $result;
    }
}
