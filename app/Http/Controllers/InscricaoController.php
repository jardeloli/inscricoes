<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Inscricao;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class InscricaoController extends Controller
{
    public function index(Evento $evento)
    {
        Gate::authorize('viewInscricoes', $evento);
        // ... listagem, como antes ...
        return $evento->inscricoes()->orderBy('nome')->get();
    }
    public function store(Request $request, Evento $evento)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:120',
            'email' => [
                'required', 'email',
                Rule::unique('inscricoes')->where('evento_id', $evento->id),
            ],
            'cpf' => 'required|digits:11',
        ]);
        if ($evento->inscricoes()->count() >= $evento->vagas) {
            return response()->json(['message' => 'Não há vagas para este evento.'], 409);
        }
        $dados['user_id'] = $request->user()->id;
        $dados['status'] = $evento->valor > 0 ? 'pendente' : 'confirmada';
        $inscricao = $evento->inscricoes()->create($dados);
        return response()->json($inscricao, 201);
    }
    public function show(Inscricao $inscricao)
    {
        Gate::authorize('view', $inscricao);
        // ... load('evento'), como antes ...
        return $inscricao->load('evento');
    }
}
