package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.state

import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeRequest

sealed class FlatPracticeUiEvent {
    data object SubmitPrevious: FlatPracticeUiEvent()
    data object SubmitNext: FlatPracticeUiEvent()
    data class Submit(val flatPracticeRequest: FlatPracticeRequest): FlatPracticeUiEvent()
}