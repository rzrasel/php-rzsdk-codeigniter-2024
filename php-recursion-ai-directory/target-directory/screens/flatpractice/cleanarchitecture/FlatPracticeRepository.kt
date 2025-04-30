package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeDto
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeRequest

interface FlatPracticeRepository {
    suspend fun getFlatPractice(flatPracticeRequest: FlatPracticeRequest): NetworkResult<FlatPracticeDto>
    suspend fun getFlatPracticeNext(flatPracticeRequest: FlatPracticeRequest): NetworkResult<FlatPracticeDto>
}