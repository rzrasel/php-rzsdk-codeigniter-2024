package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeDto
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeRequest
import retrofit2.HttpException
import java.io.IOException

class FlatPracticeRepositoryImpl(private val apiService: FlatPracticeApiService):
    FlatPracticeRepository {
    override suspend fun getFlatPractice(flatPracticeRequest: FlatPracticeRequest): NetworkResult<FlatPracticeDto> {
        return try {
            val response = apiService.getFlatPractice(flatPracticeRequest)
            //preferences.saveAuthToken(response.token)
            NetworkResult.Success(response.body())
        } catch (e: IOException){
            NetworkResult.Error("${e.message}")
        } catch (e: HttpException){
            NetworkResult.Error("${e.message}")
        }
    }
    override suspend fun getFlatPracticeNext(flatPracticeRequest: FlatPracticeRequest): NetworkResult<FlatPracticeDto> {
        return try {
            val response = apiService.getFlatPracticeNext(flatPracticeRequest)
            //preferences.saveAuthToken(response.token)
            NetworkResult.Success(response.body())
        } catch (e: IOException){
            NetworkResult.Error("${e.message}")
        } catch (e: HttpException){
            NetworkResult.Error("${e.message}")
        }
    }
}