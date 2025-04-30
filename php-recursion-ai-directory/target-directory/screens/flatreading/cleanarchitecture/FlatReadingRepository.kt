package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingDto
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingRequest

interface FlatReadingRepository {
    suspend fun getFlatReading(flatReadingRequest: FlatReadingRequest): NetworkResult<FlatReadingDto>
    suspend fun getFlatReadingNext(flatReadingRequest: FlatReadingRequest): NetworkResult<FlatReadingDto>
}