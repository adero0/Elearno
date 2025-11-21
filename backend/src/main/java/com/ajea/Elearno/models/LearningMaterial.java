package com.ajea.Elearno.models;

import jakarta.persistence.*;
import lombok.Data;

import java.util.Set;

@Entity
@Data
public class LearningMaterial {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    private String title;

    private int visual_perc;
    private int auditory_perc;
    private int kinesthetic_perc;

    @ManyToMany(mappedBy = "completedMaterials")
    private Set<AppUser> usersCompleted;
}
