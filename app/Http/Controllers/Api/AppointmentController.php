<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon; 

class AppointmentController extends Controller
{
    /**
     * Cria um novo agendamento.
     *
     * @param Request $request Os dados da requisição, contendo o nome do cliente, data e horário do agendamento.
     * @return JsonResponse
     *
     * Exemplo de requisição:
     * POST /api/appointments
     * 
     * Corpo da requisição (JSON):
     * {
     *   "customer_name": "Altamiro Silva",
     *   "date": "2025-03-10",
     *   "time": "15:30"
     * }
     * 
     * Resposta de sucesso:
     * {
     *   "id": 1,
     *   "customer_name": "Altamiro Silva",
     *   "date": "2025-03-10",
     *   "time": "15:30",
     *   "created_at": "2025-03-02T12:00:00.000000Z",
     *   "updated_at": "2025-03-02T12:00:00.000000Z"
     * }
     * 
     * Resposta de erro (horário fora do expediente):
     * {
     *   "error": "Horário fora do expediente."
     * }
     * 
     * Resposta de erro (horário já ocupado):
     * {
     *   "error": "Esse horário já está ocupado."
     * }
     */
    public function store(Request $request)
    {
        // Valida os dados da requisição para garantir que o nome do cliente, data e hora sejam fornecidos corretamente
        $request->validate([
            'customer_name' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        // Converte a data e o horário fornecidos para objetos Carbon para facilitar a manipulação
        $date = Carbon::parse($request->date);
        $time = Carbon::parse($request->time);

        // 1️⃣ Valida se o horário está dentro do expediente (08:00 - 20:00)
        if ($time->lt(Carbon::parse('08:00')) || $time->gt(Carbon::parse('20:00'))) {
            return response()->json(['error' => 'Horário fora do expediente.'], 400);
        }

        // 2️⃣ Verifica se já existe um agendamento no mesmo horário ou dentro de um intervalo de 45 minutos
        $exists = Appointment::where('date', $date->toDateString())
            ->whereBetween('time', [$time->copy()->subMinutes(44), $time->copy()->addMinutes(44)])
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Esse horário já está ocupado.'], 400);
        }

        // 3️⃣ Cria o agendamento no banco de dados com os dados fornecidos
        $appointment = Appointment::create([
            'customer_name' => $request->customer_name,
            'date' => $date->toDateString(),
            'time' => $time->toTimeString(),
        ]);

        // Retorna a resposta com os detalhes do agendamento criado
        return response()->json($appointment, 201);
    }


    /**
     * Lista todos os agendamentos para uma data específica.
     *
     * @param Request $request A requisição contendo a data para filtrar os agendamentos.
     * @return JsonResponse
     *
     * Exemplo de requisição:
     * GET /api/appointments?date=2025-03-10
     * 
     * Resposta de sucesso:
     * [
     *   {
     *     "id": 1,
     *     "customer_name": "Altamiro Silva",
     *     "date": "2025-03-10",
     *     "time": "15:30",
     *     "created_at": "2025-03-02T12:00:00.000000Z",
     *     "updated_at": "2025-03-02T12:00:00.000000Z"
     *   }
     * ]
     * 
     * Resposta de erro (data não fornecida):
     * {
     *   "error": "A data é obrigatória."
     * }
     */
    public function index(Request $request)
    {
        // Verifica se a data foi fornecida na requisição
        $date = $request->query('date');
    
        if (!$date) {
            return response()->json(['error' => 'A data é obrigatória.'], 400);
        }
    
        // Busca todos os agendamentos para a data especificada
        $appointments = Appointment::where('date', $date)->get();
    
        // Retorna a lista de agendamentos encontrados
        return response()->json($appointments);
    }
}
