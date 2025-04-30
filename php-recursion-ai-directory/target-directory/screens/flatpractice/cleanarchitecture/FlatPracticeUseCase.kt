package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture

import com.rz.logwriter.DebugLog
import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeModel
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeRequest
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.toModel

//import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.toFlatPracticeModel

class FlatPracticeUseCase(private val repository: FlatPracticeRepository) {
    suspend fun execute(userAuthToken: String?, categoryId: String, itemId: String): NetworkResult<FlatPracticeModel> {
        val userAuthTokenVal: String = userAuthToken ?: ""
        DebugLog.Log("User auth token: $userAuthTokenVal")
        val flatPracticeRequest = FlatPracticeRequest(
            userAuthToken = userAuthTokenVal.trim(),
            categoryId = categoryId,
            itemId = itemId,
        )

        val remoteUser = repository.getFlatPractice(flatPracticeRequest)
        //repository.saveUserLoginSession(remoteUser)
        return NetworkResult.Success(remoteUser.data?.toModel())
    }
    suspend fun executeNext(flatPracticeRequest: FlatPracticeRequest): NetworkResult<FlatPracticeModel> {
        /*val userAuthTokenVal: String = userAuthToken ?: ""
        val flatPracticeRequest = FlatPracticeRequest(
            userAuthToken = userAuthTokenVal.trim(),
            categoryId = categoryId,
            itemId = itemId,
        )*/

        val remoteUser = repository.getFlatPracticeNext(flatPracticeRequest)
        //repository.saveUserLoginSession(remoteUser)
        return NetworkResult.Success(remoteUser.data?.toModel())
    }
}