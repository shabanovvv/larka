<?php

namespace App\Http\Controllers\Admin;

use App\DTO\SortDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechnologyStoreRequest;
use App\Http\Requests\Admin\TechnologyUpdateRequest;
use App\Models\Technology;
use App\Services\TechnologyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * CRUD-контроллер админки для управления технологиями.
 */
class TechnologyController extends Controller
{
    /**
     * @param TechnologyService $technologyService Сервис работы с технологиями.
     */
    public function __construct(private readonly TechnologyService $technologyService)
    {}

    /**
     * Список технологий с пагинацией.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $sortDTO = SortDTO::fromRequest($request);

        return view('admin.technologies.index', [
            'technologies' => $this->technologyService->paginate(20, $sortDTO),
        ]);
    }

    /**
     * Форма создания технологии.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.technologies.create');
    }

    /**
     * Сохранение новой технологии.
     *
     * @param TechnologyStoreRequest $request
     * @return RedirectResponse
     */
    public function store(TechnologyStoreRequest $request): RedirectResponse
    {
        $technology = $this->technologyService->store(
            $request->validated()
        );

        return redirect()
            ->route('admin.technologies.index')
            ->with('success', 'Технология успешно создана.');
    }

    /**
     * Форма редактирования технологии.
     *
     * @param Technology $technology
     * @return View
     */
    public function edit(Technology $technology): View
    {
        return view('admin.technologies.edit', [
            'technology' => $technology,
        ]);
    }

    /**
     * Обновление существующей технологии.
     *
     * @param TechnologyUpdateRequest $request
     * @param Technology $technology
     * @return RedirectResponse
     */
    public function update(TechnologyUpdateRequest $request, Technology $technology): RedirectResponse
    {
        $this->technologyService->update($technology, $request->validated());

        return redirect()
            ->route('admin.technologies.index')
            ->with('success', 'Технология успешно обновлена.');
    }

    /**
     * Удаление технологии.
     *
     * @param Technology $technology
     * @return RedirectResponse
     */
    public function destroy(Technology $technology): RedirectResponse
    {
        $this->technologyService->delete($technology);

        return redirect()
            ->route('admin.technologies.index')
            ->with('success', 'Технология успешно удалена');
    }
}
