package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingModel
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingRequest
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.toModel

class FlatReadingUseCase(private val repository: FlatReadingRepository) {
    suspend fun execute(userAuthToken: String?, categoryId: String, itemId: String, currentIndex: Int): NetworkResult<FlatReadingModel> {
        val userAuthTokenVal: String = userAuthToken ?: ""
        val flatReadingRequest = FlatReadingRequest(
            userAuthToken = userAuthTokenVal.trim(),
            categoryId = categoryId,
            itemId = itemId,
            currentQuestionIndex = currentIndex,
        )

        val remoteUser = repository.getFlatReading(flatReadingRequest)
        //repository.saveUserLoginSession(remoteUser)
        return NetworkResult.Success(remoteUser.data?.toModel())
    }
    suspend fun executeNext(userAuthToken: String?, categoryId: String, itemId: String, questionId: String): NetworkResult<FlatReadingModel> {
        val userAuthTokenVal: String = userAuthToken ?: ""
        val flatReadingRequest = FlatReadingRequest(
            userAuthToken = userAuthTokenVal.trim(),
            categoryId = categoryId,
            itemId = itemId,
            questionId = questionId,
        )

        val remoteUser = repository.getFlatReadingNext(flatReadingRequest)
        //repository.saveUserLoginSession(remoteUser)
        return NetworkResult.Success(remoteUser.data?.toModel())
    }
}