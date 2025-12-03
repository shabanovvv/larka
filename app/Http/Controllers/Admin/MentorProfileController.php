<?php

namespace App\Http\Controllers\Admin;

use App\DTO\SortDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MentorProfileStoreRequest;
use App\Http\Requests\Admin\MentorProfileUpdateRequest;
use App\Models\MentorProfile;
use App\Services\MentorProfileService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * CRUD-контроллер для управления профилями менторов в админке.
 */
class MentorProfileController extends Controller
{
    /**
     * @param MentorProfileService $mentorProfileService Сервис работы с профилями менторов.
     */
    public function __construct(private readonly MentorProfileService $mentorProfileService)
    {}

    /**
     * Список профилей менторов с пагинацией.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $sortDTO = SortDTO::fromRequest($request);

        return view('admin.mentor-profile.index', [
            'mentorProfiles' => $this->mentorProfileService->paginate(20, $sortDTO),
        ]);
    }

    /**
     * Форма создания профиля ментора.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.mentor-profile.create');
    }

    /**
     * Сохранение нового профиля ментора.
     *
     * @param MentorProfileStoreRequest $request
     * @return RedirectResponse
     */
    public function store(MentorProfileStoreRequest $request): RedirectResponse
    {
        $this->mentorProfileService->store($request->validated());

        return redirect()
            ->route('admin.mentor-profile.index')
            ->with('success', 'Профиль ментора успешно создан.');
    }

    /**
     * Форма редактирования профиля ментора.
     *
     * @param MentorProfile $mentorProfile
     * @return View
     */
    public function edit(MentorProfile $mentorProfile): View
    {
        return view('admin.mentor-profile.edit', [
            'mentorProfile' => $mentorProfile,
        ]);
    }

    /**
     * Обновление существующего профиля ментора.
     *
     * @param MentorProfileUpdateRequest $request
     * @param MentorProfile $mentorProfile
     * @return RedirectResponse
     */
    public function update(MentorProfileUpdateRequest $request, MentorProfile $mentorProfile): RedirectResponse
    {
        $this->mentorProfileService->update($mentorProfile, $request->validated());

        return redirect()
            ->route('admin.mentor-profile.index')
            ->with('success', 'Профиль ментора успешно обновлён.');
    }

    /**
     * Удаление профиля ментора.
     *
     * @param MentorProfile $mentorProfile
     * @return RedirectResponse
     */
    public function destroy(MentorProfile $mentorProfile): RedirectResponse
    {
        $this->mentorProfileService->delete($mentorProfile);

        return redirect()
            ->route('admin.mentor-profile.index')
            ->with('success', 'Профиль ментора успешно удалён.');
    }
}
