package com.ajea.Elearno.models;

import lombok.Data;

@Data
public class LearningStyleUpdateRequest {
    private int visualCount;
    private int auditoryCount;
    private int kinestheticCount;
    private String dominantStyle;
}
