package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture

import android.util.Log
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeAnswerItemModel
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeModel
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeQuestionItemModel
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeRequest
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.state.FlatPracticeUiEvent
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.state.FlatPracticeUiState
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

class FlatPracticeViewModel(private val flatPracticeUseCase: FlatPracticeUseCase): ViewModel() {
    private val _uiState = MutableStateFlow<FlatPracticeUiState>(FlatPracticeUiState.Idle)
    val uiState: StateFlow<FlatPracticeUiState> = _uiState
    //
    /*private val _mcqQuestionState = MutableStateFlow<FlatPracticeModel>(
        FlatPracticeModel("", false, arrayListOf())
    )*/
    private val _mcqQuestionState = MutableStateFlow<FlatPracticeModel>(
        FlatPracticeModel()
    )
    val mcqQuestionState: StateFlow<FlatPracticeModel> = _mcqQuestionState
    //

    fun getFlatPracticeInitial(userAuthToken: String? = "", categoryId: String, itemId: String) {
        viewModelScope.launch(Dispatchers.IO) {
            _uiState.value = FlatPracticeUiState.Loading
            val flatPracticeResult = flatPracticeUseCase.execute(userAuthToken, categoryId, itemId)
            when(flatPracticeResult) {
                is NetworkResult.Loading -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Loading Data ${flatPracticeResult.data}")
                }
                is NetworkResult.Success -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Success Data ${flatPracticeResult.data}")
                    //_dashboardModel.value = dashboardResult.data
                    //_dashboardModel.postValue(flatReadingResult.data)
                    flatPracticeResult.data?.let {
                        //_dashboardModel.value?.userAuthToken = it.userAuthToken
                        _mcqQuestionState.value = it
                        _uiState.value = FlatPracticeUiState.Success(it)
                    } ?: run {
                        _uiState.value = FlatPracticeUiState.Error("Null data")
                    }
                }
                is NetworkResult.Error -> {
                    /*effects.send(
                        BaseContract.Effect.Error(
                            it.message ?: ErrorsMessage.gotApiCallError
                        )
                    )*/
                    //Log.e("DEBUG_LOG", it.message.toString())
                    Log.e("DEBUG_LOG", "Error Data ${flatPracticeResult.data}")
                    flatPracticeResult.message?.let {
                        _uiState.value = FlatPracticeUiState.Error(it)
                    } ?: run {
                        _uiState.value = FlatPracticeUiState.Error("Unknown error")
                    }
                }
            }
        }
    }

    fun toggleOption(index: Int, optionIndex: Int) {
        //val question: FlatPracticeQuestionItemModel = _mcqQuestionState.value.data.questionSet[index]
        /*val question: FlatPracticeQuestionItemModel? =
            _mcqQuestionState.value.data?.questionSet?.get(index)
        val options: ArrayList<FlatPracticeAnswerItemModel> = question?.answerSet?.mapIndexed { point, option ->
            if (point == optionIndex) option.copy(isSelected = !option.isSelected) else option
        }.toCollection(ArrayList())
        val flatPracticeModel: FlatPracticeModel = _mcqQuestionState.value
        flatPracticeModel.data?.questionSet?.get(index)?.answerSet = options
        _mcqQuestionState.value = flatPracticeModel*/
    }

    fun onUiEvent(flatPracticeUiEvent: FlatPracticeUiEvent) {
        when(flatPracticeUiEvent) {
            is FlatPracticeUiEvent.SubmitPrevious -> {}

            is FlatPracticeUiEvent.SubmitNext -> {}
            is FlatPracticeUiEvent.Submit -> {
                val flatPracticeRequest: FlatPracticeRequest = (flatPracticeUiEvent as FlatPracticeUiEvent.Submit).flatPracticeRequest
                getFlatPracticeNext(flatPracticeRequest)
            }
        }
    }

    private fun getFlatPracticeNext(flatPracticeRequest: FlatPracticeRequest) {
        viewModelScope.launch(Dispatchers.IO) {
            _uiState.value = FlatPracticeUiState.Loading
            val flatPracticeResult = flatPracticeUseCase.executeNext(flatPracticeRequest)
            when(flatPracticeResult) {
                is NetworkResult.Loading -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Loading Data ${flatPracticeResult.data}")
                }
                is NetworkResult.Success -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Success Data ${flatPracticeResult.data}")
                    //_dashboardModel.value = dashboardResult.data
                    //_dashboardModel.postValue(flatReadingResult.data)
                    flatPracticeResult.data?.let {
                        //_dashboardModel.value?.userAuthToken = it.userAuthToken
                        _mcqQuestionState.value = it
                        _uiState.value = FlatPracticeUiState.Success(it)
                    } ?: run {
                        _uiState.value = FlatPracticeUiState.Error("Null data")
                    }
                }
                is NetworkResult.Error -> {
                    /*effects.send(
                        BaseContract.Effect.Error(
                            it.message ?: ErrorsMessage.gotApiCallError
                        )
                    )*/
                    //Log.e("DEBUG_LOG", it.message.toString())
                    Log.e("DEBUG_LOG", "Error Data ${flatPracticeResult.data}")
                    flatPracticeResult.message?.let {
                        _uiState.value = FlatPracticeUiState.Error(it)
                    } ?: run {
                        _uiState.value = FlatPracticeUiState.Error("Unknown error")
                    }
                }
            }
        }
    }
}