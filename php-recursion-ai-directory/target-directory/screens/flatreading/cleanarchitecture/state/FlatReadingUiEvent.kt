package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.state

import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingRequest

sealed class FlatReadingUiEvent {
    data object SubmitPrevious: FlatReadingUiEvent()
    data object SubmitNext: FlatReadingUiEvent()
    data class Submit(val flatReadingRequest: FlatReadingRequest): FlatReadingUiEvent()
}